import pool from '../config/database.js';
import { v4 as uuidv4 } from 'uuid';
import { sendOrderNotification } from '../utils/email.js';

// Create order
export const createOrder = async (req, res) => {
  const connection = await pool.getConnection();
  
  try {
    const { items, payment_method, notes } = req.body;
    // items: [{ product_id, quantity }]

    if (!items || items.length === 0) {
      return res.status(400).json({ 
        success: false, 
        message: 'No items in order' 
      });
    }

    await connection.beginTransaction();

    // Get product details and validate stock
    const productIds = items.map(item => item.product_id);
    const [products] = await connection.query(
      'SELECT * FROM products WHERE id IN (?) AND status = "active"',
      [productIds]
    );

    if (products.length !== items.length) {
      await connection.rollback();
      return res.status(400).json({ 
        success: false, 
        message: 'Some products not found or inactive' 
      });
    }

    // Check stock
    for (const item of items) {
      const product = products.find(p => p.id === item.product_id);
      if (product.stock < item.quantity) {
        await connection.rollback();
        return res.status(400).json({ 
          success: false, 
          message: `Insufficient stock for ${product.title}` 
        });
      }
    }

    // Group by seller
    const ordersBySeller = {};
    
    for (const item of items) {
      const product = products.find(p => p.id === item.product_id);
      
      if (!ordersBySeller[product.seller_id]) {
        ordersBySeller[product.seller_id] = {
          seller_id: product.seller_id,
          items: [],
          total: 0
        };
      }
      
      ordersBySeller[product.seller_id].items.push({
        ...item,
        product
      });
      ordersBySeller[product.seller_id].total += product.price * item.quantity;
    }

    const createdOrders = [];

    // Create orders for each seller
    for (const sellerId in ordersBySeller) {
      const orderData = ordersBySeller[sellerId];
      const orderNumber = `ORD-${Date.now()}-${uuidv4().substring(0, 8).toUpperCase()}`;
      
      const platformFee = orderData.total * (parseFloat(process.env.PLATFORM_FEE) / 100);
      const sellerAmount = orderData.total - platformFee;

      // Create order
      const [orderResult] = await connection.query(`
        INSERT INTO orders 
        (order_number, buyer_id, seller_id, total_price, platform_fee, seller_amount, payment_method, notes, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')
      `, [
        orderNumber,
        req.user.id,
        sellerId,
        orderData.total,
        platformFee,
        sellerAmount,
        payment_method,
        notes || null
      ]);

      const orderId = orderResult.insertId;

      // Create order items and update stock
      for (const item of orderData.items) {
        await connection.query(`
          INSERT INTO order_items 
          (order_id, product_id, product_title, quantity, price, digital_content)
          VALUES (?, ?, ?, ?, ?, ?)
        `, [
          orderId,
          item.product_id,
          item.product.title,
          item.quantity,
          item.product.price,
          item.product.auto_delivery ? item.product.delivery_content : null
        ]);

        // Update stock
        await connection.query(
          'UPDATE products SET stock = stock - ? WHERE id = ?',
          [item.quantity, item.product_id]
        );
      }

      createdOrders.push({
        orderId,
        orderNumber,
        total: orderData.total
      });

      // Send email notification
      const [users] = await connection.query(
        'SELECT email, name FROM users WHERE id = ?',
        [req.user.id]
      );

      if (users.length > 0) {
        await sendOrderNotification(
          users[0].email,
          users[0].name,
          orderNumber,
          orderData.total
        );
      }
    }

    await connection.commit();

    res.status(201).json({
      success: true,
      message: 'Order created successfully',
      data: { orders: createdOrders }
    });
  } catch (error) {
    await connection.rollback();
    console.error('Create order error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to create order', 
      error: error.message 
    });
  } finally {
    connection.release();
  }
};

// Upload payment proof
export const uploadPaymentProof = async (req, res) => {
  try {
    const { id } = req.params;
    const { payment_proof } = req.body;

    const [orders] = await pool.query(
      'SELECT buyer_id FROM orders WHERE id = ?',
      [id]
    );

    if (orders.length === 0) {
      return res.status(404).json({ 
        success: false, 
        message: 'Order not found' 
      });
    }

    if (orders[0].buyer_id !== req.user.id) {
      return res.status(403).json({ 
        success: false, 
        message: 'Access denied' 
      });
    }

    await pool.query(
      'UPDATE orders SET payment_proof = ?, status = "paid" WHERE id = ?',
      [payment_proof, id]
    );

    res.json({
      success: true,
      message: 'Payment proof uploaded successfully'
    });
  } catch (error) {
    console.error('Upload payment proof error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to upload payment proof', 
      error: error.message 
    });
  }
};

// Confirm order (Buyer)
export const confirmOrder = async (req, res) => {
  const connection = await pool.getConnection();
  
  try {
    const { id } = req.params;

    await connection.beginTransaction();

    const [orders] = await connection.query(
      'SELECT * FROM orders WHERE id = ?',
      [id]
    );

    if (orders.length === 0) {
      await connection.rollback();
      return res.status(404).json({ 
        success: false, 
        message: 'Order not found' 
      });
    }

    const order = orders[0];

    if (order.buyer_id !== req.user.id) {
      await connection.rollback();
      return res.status(403).json({ 
        success: false, 
        message: 'Access denied' 
      });
    }

    if (order.status !== 'processing') {
      await connection.rollback();
      return res.status(400).json({ 
        success: false, 
        message: 'Order cannot be confirmed in current status' 
      });
    }

    // Update order status
    await connection.query(
      'UPDATE orders SET status = "completed", escrow_status = "released", completed_at = NOW() WHERE id = ?',
      [id]
    );

    // Release funds to seller
    const [sellerWallet] = await connection.query(
      'SELECT id, balance FROM wallets WHERE user_id = ?',
      [order.seller_id]
    );

    if (sellerWallet.length > 0) {
      const walletId = sellerWallet[0].id;
      const balanceBefore = parseFloat(sellerWallet[0].balance);
      const balanceAfter = balanceBefore + parseFloat(order.seller_amount);

      await connection.query(
        'UPDATE wallets SET balance = ? WHERE id = ?',
        [balanceAfter, walletId]
      );

      await connection.query(`
        INSERT INTO wallet_transactions 
        (wallet_id, user_id, type, amount, balance_before, balance_after, status, reference_type, reference_id, description)
        VALUES (?, ?, 'earning', ?, ?, ?, 'completed', 'order', ?, ?)
      `, [
        walletId,
        order.seller_id,
        order.seller_amount,
        balanceBefore,
        balanceAfter,
        id,
        `Earning from order ${order.order_number}`
      ]);
    }

    // Update product sold count
    await connection.query(`
      UPDATE products p
      JOIN order_items oi ON p.id = oi.product_id
      SET p.sold_count = p.sold_count + oi.quantity
      WHERE oi.order_id = ?
    `, [id]);

    await connection.commit();

    res.json({
      success: true,
      message: 'Order confirmed successfully'
    });
  } catch (error) {
    await connection.rollback();
    console.error('Confirm order error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to confirm order', 
      error: error.message 
    });
  } finally {
    connection.release();
  }
};

// Process order (Seller)
export const processOrder = async (req, res) => {
  try {
    const { id } = req.params;

    const [orders] = await pool.query(
      'SELECT * FROM orders WHERE id = ?',
      [id]
    );

    if (orders.length === 0) {
      return res.status(404).json({ 
        success: false, 
        message: 'Order not found' 
      });
    }

    const order = orders[0];

    if (order.seller_id !== req.user.id) {
      return res.status(403).json({ 
        success: false, 
        message: 'Access denied' 
      });
    }

    if (order.status !== 'paid') {
      return res.status(400).json({ 
        success: false, 
        message: 'Order must be paid first' 
      });
    }

    await pool.query(
      'UPDATE orders SET status = "processing" WHERE id = ?',
      [id]
    );

    // Mark auto-delivery items as delivered
    await pool.query(
      'UPDATE order_items SET delivered = TRUE, delivered_at = NOW() WHERE order_id = ? AND digital_content IS NOT NULL',
      [id]
    );

    res.json({
      success: true,
      message: 'Order is being processed'
    });
  } catch (error) {
    console.error('Process order error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to process order', 
      error: error.message 
    });
  }
};

// Get buyer orders
export const getBuyerOrders = async (req, res) => {
  try {
    const { status, page = 1, limit = 20 } = req.query;
    const offset = (page - 1) * limit;

    let query = `
      SELECT o.*, u.name as seller_name,
      (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count
      FROM orders o
      JOIN users u ON o.seller_id = u.id
      WHERE o.buyer_id = ?
    `;

    const params = [req.user.id];

    if (status) {
      query += ' AND o.status = ?';
      params.push(status);
    }

    query += ' ORDER BY o.created_at DESC LIMIT ? OFFSET ?';
    params.push(parseInt(limit), parseInt(offset));

    const [orders] = await pool.query(query, params);

    // Get order items
    for (const order of orders) {
      const [items] = await pool.query(
        'SELECT * FROM order_items WHERE order_id = ?',
        [order.id]
      );
      order.items = items;
    }

    const [countResult] = await pool.query(
      'SELECT COUNT(*) as total FROM orders WHERE buyer_id = ?',
      [req.user.id]
    );

    res.json({
      success: true,
      data: {
        orders,
        pagination: {
          page: parseInt(page),
          limit: parseInt(limit),
          total: countResult[0].total,
          totalPages: Math.ceil(countResult[0].total / limit)
        }
      }
    });
  } catch (error) {
    console.error('Get buyer orders error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to get orders', 
      error: error.message 
    });
  }
};

// Get seller orders
export const getSellerOrders = async (req, res) => {
  try {
    const { status, page = 1, limit = 20 } = req.query;
    const offset = (page - 1) * limit;

    let query = `
      SELECT o.*, u.name as buyer_name, u.email as buyer_email,
      (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count
      FROM orders o
      JOIN users u ON o.buyer_id = u.id
      WHERE o.seller_id = ?
    `;

    const params = [req.user.id];

    if (status) {
      query += ' AND o.status = ?';
      params.push(status);
    }

    query += ' ORDER BY o.created_at DESC LIMIT ? OFFSET ?';
    params.push(parseInt(limit), parseInt(offset));

    const [orders] = await pool.query(query, params);

    // Get order items
    for (const order of orders) {
      const [items] = await pool.query(
        'SELECT * FROM order_items WHERE order_id = ?',
        [order.id]
      );
      order.items = items;
    }

    const [countResult] = await pool.query(
      'SELECT COUNT(*) as total FROM orders WHERE seller_id = ?',
      [req.user.id]
    );

    res.json({
      success: true,
      data: {
        orders,
        pagination: {
          page: parseInt(page),
          limit: parseInt(limit),
          total: countResult[0].total,
          totalPages: Math.ceil(countResult[0].total / limit)
        }
      }
    });
  } catch (error) {
    console.error('Get seller orders error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to get orders', 
      error: error.message 
    });
  }
};

// Get order by ID
export const getOrderById = async (req, res) => {
  try {
    const { id } = req.params;

    const [orders] = await pool.query(`
      SELECT o.*, 
      buyer.name as buyer_name, buyer.email as buyer_email,
      seller.name as seller_name, seller.email as seller_email
      FROM orders o
      JOIN users buyer ON o.buyer_id = buyer.id
      JOIN users seller ON o.seller_id = seller.id
      WHERE o.id = ?
    `, [id]);

    if (orders.length === 0) {
      return res.status(404).json({ 
        success: false, 
        message: 'Order not found' 
      });
    }

    const order = orders[0];

    if (order.buyer_id !== req.user.id && order.seller_id !== req.user.id && req.user.role !== 'admin') {
      return res.status(403).json({ 
        success: false, 
        message: 'Access denied' 
      });
    }

    const [items] = await pool.query(
      'SELECT * FROM order_items WHERE order_id = ?',
      [id]
    );

    order.items = items;

    res.json({
      success: true,
      data: order
    });
  } catch (error) {
    console.error('Get order error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to get order', 
      error: error.message 
    });
  }
};

// Cancel order
export const cancelOrder = async (req, res) => {
  const connection = await pool.getConnection();
  
  try {
    const { id } = req.params;
    const { reason } = req.body;

    await connection.beginTransaction();

    const [orders] = await connection.query(
      'SELECT * FROM orders WHERE id = ?',
      [id]
    );

    if (orders.length === 0) {
      await connection.rollback();
      return res.status(404).json({ 
        success: false, 
        message: 'Order not found' 
      });
    }

    const order = orders[0];

    if (order.buyer_id !== req.user.id && req.user.role !== 'admin') {
      await connection.rollback();
      return res.status(403).json({ 
        success: false, 
        message: 'Access denied' 
      });
    }

    if (order.status === 'completed' || order.status === 'cancelled') {
      await connection.rollback();
      return res.status(400).json({ 
        success: false, 
        message: 'Order cannot be cancelled' 
      });
    }

    // Restore stock
    await connection.query(`
      UPDATE products p
      JOIN order_items oi ON p.id = oi.product_id
      SET p.stock = p.stock + oi.quantity
      WHERE oi.order_id = ?
    `, [id]);

    // Update order status
    await connection.query(
      'UPDATE orders SET status = "cancelled", notes = ? WHERE id = ?',
      [reason || 'Cancelled by buyer', id]
    );

    await connection.commit();

    res.json({
      success: true,
      message: 'Order cancelled successfully'
    });
  } catch (error) {
    await connection.rollback();
    console.error('Cancel order error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to cancel order', 
      error: error.message 
    });
  } finally {
    connection.release();
  }
};

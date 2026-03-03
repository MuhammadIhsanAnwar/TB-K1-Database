import pool from '../config/database.js';

// Get dashboard analytics
export const getDashboardAnalytics = async (req, res) => {
  try {
    // Total users by role
    const [userStats] = await pool.query(`
      SELECT role, COUNT(*) as count
      FROM users
      GROUP BY role
    `);

    // Total products by status
    const [productStats] = await pool.query(`
      SELECT status, COUNT(*) as count
      FROM products
      GROUP BY status
    `);

    // Total orders by status
    const [orderStats] = await pool.query(`
      SELECT status, COUNT(*) as count, SUM(total_price) as total_revenue
      FROM orders
      GROUP BY status
    `);

    // Recent transactions
    const [recentOrders] = await pool.query(`
      SELECT o.*, buyer.name as buyer_name, seller.name as seller_name
      FROM orders o
      JOIN users buyer ON o.buyer_id = buyer.id
      JOIN users seller ON o.seller_id = seller.id
      ORDER BY o.created_at DESC
      LIMIT 10
    `);

    // Platform revenue (total fees)
    const [revenueStats] = await pool.query(`
      SELECT 
        SUM(platform_fee) as total_platform_fee,
        COUNT(*) as completed_orders
      FROM orders
      WHERE status = 'completed'
    `);

    // Pending approvals
    const [pendingProducts] = await pool.query(
      'SELECT COUNT(*) as count FROM products WHERE status = "pending"'
    );

    const [pendingTransactions] = await pool.query(
      'SELECT COUNT(*) as count FROM wallet_transactions WHERE status = "pending"'
    );

    res.json({
      success: true,
      data: {
        users: userStats,
        products: productStats,
        orders: orderStats,
        revenue: revenueStats[0],
        recentOrders,
        pending: {
          products: pendingProducts[0].count,
          transactions: pendingTransactions[0].count
        }
      }
    });
  } catch (error) {
    console.error('Get analytics error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to get analytics', 
      error: error.message 
    });
  }
};

// Get all users
export const getAllUsers = async (req, res) => {
  try {
    const { role, search, page = 1, limit = 20 } = req.query;
    const offset = (page - 1) * limit;

    let query = `
      SELECT id, name, email, role, phone, verified, is_suspended, seller_verified, seller_level, created_at
      FROM users
      WHERE 1=1
    `;

    const params = [];

    if (role) {
      query += ' AND role = ?';
      params.push(role);
    }

    if (search) {
      query += ' AND (name LIKE ? OR email LIKE ?)';
      params.push(`%${search}%`, `%${search}%`);
    }

    query += ' ORDER BY created_at DESC LIMIT ? OFFSET ?';
    params.push(parseInt(limit), parseInt(offset));

    const [users] = await pool.query(query, params);

    let countQuery = 'SELECT COUNT(*) as total FROM users WHERE 1=1';
    const countParams = [];

    if (role) {
      countQuery += ' AND role = ?';
      countParams.push(role);
    }

    if (search) {
      countQuery += ' AND (name LIKE ? OR email LIKE ?)';
      countParams.push(`%${search}%`, `%${search}%`);
    }

    const [countResult] = await pool.query(countQuery, countParams);

    res.json({
      success: true,
      data: {
        users,
        pagination: {
          page: parseInt(page),
          limit: parseInt(limit),
          total: countResult[0].total,
          totalPages: Math.ceil(countResult[0].total / limit)
        }
      }
    });
  } catch (error) {
    console.error('Get users error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to get users', 
      error: error.message 
    });
  }
};

// Suspend/Unsuspend user
export const toggleSuspendUser = async (req, res) => {
  try {
    const { id } = req.params;
    const { suspend, reason } = req.body;

    const [users] = await pool.query(
      'SELECT is_suspended FROM users WHERE id = ?',
      [id]
    );

    if (users.length === 0) {
      return res.status(404).json({ 
        success: false, 
        message: 'User not found' 
      });
    }

    await pool.query(
      'UPDATE users SET is_suspended = ? WHERE id = ?',
      [suspend, id]
    );

    res.json({
      success: true,
      message: suspend ? 'User suspended' : 'User unsuspended'
    });
  } catch (error) {
    console.error('Suspend user error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to update user status', 
      error: error.message 
    });
  }
};

// Verify seller
export const verifySeller = async (req, res) => {
  try {
    const { id } = req.params;

    const [users] = await pool.query(
      'SELECT role FROM users WHERE id = ?',
      [id]
    );

    if (users.length === 0) {
      return res.status(404).json({ 
        success: false, 
        message: 'User not found' 
      });
    }

    if (users[0].role !== 'seller') {
      return res.status(400).json({ 
        success: false, 
        message: 'User is not a seller' 
      });
    }

    await pool.query(
      'UPDATE users SET seller_verified = TRUE WHERE id = ?',
      [id]
    );

    res.json({
      success: true,
      message: 'Seller verified successfully'
    });
  } catch (error) {
    console.error('Verify seller error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to verify seller', 
      error: error.message 
    });
  }
};

// Get all products (including pending)
export const getAllProducts = async (req, res) => {
  try {
    const { status, page = 1, limit = 20 } = req.query;
    const offset = (page - 1) * limit;

    let query = `
      SELECT p.*, c.name as category_name, u.name as seller_name
      FROM products p
      JOIN categories c ON p.category_id = c.id
      JOIN users u ON p.seller_id = u.id
      WHERE 1=1
    `;

    const params = [];

    if (status) {
      query += ' AND p.status = ?';
      params.push(status);
    }

    query += ' ORDER BY p.created_at DESC LIMIT ? OFFSET ?';
    params.push(parseInt(limit), parseInt(offset));

    const [products] = await pool.query(query, params);

    let countQuery = 'SELECT COUNT(*) as total FROM products WHERE 1=1';
    const countParams = [];

    if (status) {
      countQuery += ' AND status = ?';
      countParams.push(status);
    }

    const [countResult] = await pool.query(countQuery, countParams);

    res.json({
      success: true,
      data: {
        products,
        pagination: {
          page: parseInt(page),
          limit: parseInt(limit),
          total: countResult[0].total,
          totalPages: Math.ceil(countResult[0].total / limit)
        }
      }
    });
  } catch (error) {
    console.error('Get products error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to get products', 
      error: error.message 
    });
  }
};

// Moderate product
export const moderateProduct = async (req, res) => {
  try {
    const { id } = req.params;
    const { status, reason } = req.body;

    const validStatuses = ['active', 'rejected', 'inactive'];
    
    if (!validStatuses.includes(status)) {
      return res.status(400).json({ 
        success: false, 
        message: 'Invalid status' 
      });
    }

    await pool.query(
      'UPDATE products SET status = ? WHERE id = ?',
      [status, id]
    );

    res.json({
      success: true,
      message: `Product ${status} successfully`
    });
  } catch (error) {
    console.error('Moderate product error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to moderate product', 
      error: error.message 
    });
  }
};

// Get all transactions
export const getAllTransactions = async (req, res) => {
  try {
    const { status, page = 1, limit = 20 } = req.query;
    const offset = (page - 1) * limit;

    let query = `
      SELECT o.*, buyer.name as buyer_name, seller.name as seller_name
      FROM orders o
      JOIN users buyer ON o.buyer_id = buyer.id
      JOIN users seller ON o.seller_id = seller.id
      WHERE 1=1
    `;

    const params = [];

    if (status) {
      query += ' AND o.status = ?';
      params.push(status);
    }

    query += ' ORDER BY o.created_at DESC LIMIT ? OFFSET ?';
    params.push(parseInt(limit), parseInt(offset));

    const [orders] = await pool.query(query, params);

    let countQuery = 'SELECT COUNT(*) as total FROM orders WHERE 1=1';
    const countParams = [];

    if (status) {
      countQuery += ' AND status = ?';
      countParams.push(status);
    }

    const [countResult] = await pool.query(countQuery, countParams);

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
    console.error('Get transactions error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to get transactions', 
      error: error.message 
    });
  }
};

// Get disputes
export const getDisputes = async (req, res) => {
  try {
    const { status, page = 1, limit = 20 } = req.query;
    const offset = (page - 1) * limit;

    let query = `
      SELECT d.*, o.order_number, u.name as initiator_name
      FROM disputes d
      JOIN orders o ON d.order_id = o.id
      JOIN users u ON d.initiator_id = u.id
      WHERE 1=1
    `;

    const params = [];

    if (status) {
      query += ' AND d.status = ?';
      params.push(status);
    }

    query += ' ORDER BY d.created_at DESC LIMIT ? OFFSET ?';
    params.push(parseInt(limit), parseInt(offset));

    const [disputes] = await pool.query(query, params);

    let countQuery = 'SELECT COUNT(*) as total FROM disputes WHERE 1=1';
    const countParams = [];

    if (status) {
      countQuery += ' AND status = ?';
      countParams.push(status);
    }

    const [countResult] = await pool.query(countQuery, countParams);

    res.json({
      success: true,
      data: {
        disputes,
        pagination: {
          page: parseInt(page),
          limit: parseInt(limit),
          total: countResult[0].total,
          totalPages: Math.ceil(countResult[0].total / limit)
        }
      }
    });
  } catch (error) {
    console.error('Get disputes error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to get disputes', 
      error: error.message 
    });
  }
};

// Resolve dispute
export const resolveDispute = async (req, res) => {
  try {
    const { id } = req.params;
    const { resolution } = req.body;

    await pool.query(
      'UPDATE disputes SET status = "resolved", resolution = ?, resolved_by = ?, resolved_at = NOW() WHERE id = ?',
      [resolution, req.user.id, id]
    );

    res.json({
      success: true,
      message: 'Dispute resolved successfully'
    });
  } catch (error) {
    console.error('Resolve dispute error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to resolve dispute', 
      error: error.message 
    });
  }
};

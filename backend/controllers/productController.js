import pool from '../config/database.js';

// Get all products with filters
export const getProducts = async (req, res) => {
  try {
    const { 
      category, 
      search, 
      minPrice, 
      maxPrice, 
      sort = 'newest',
      page = 1,
      limit = 20
    } = req.query;

    let query = `
      SELECT p.*, c.name as category_name, u.name as seller_name, u.seller_level,
      (SELECT AVG(rating) FROM reviews WHERE product_id = p.id) as avg_rating,
      (SELECT COUNT(*) FROM reviews WHERE product_id = p.id) as review_count
      FROM products p
      JOIN categories c ON p.category_id = c.id
      JOIN users u ON p.seller_id = u.id
      WHERE p.status = 'active'
    `;

    const params = [];

    if (category) {
      query += ' AND p.category_id = ?';
      params.push(category);
    }

    if (search) {
      query += ' AND (p.title LIKE ? OR p.description LIKE ?)';
      params.push(`%${search}%`, `%${search}%`);
    }

    if (minPrice) {
      query += ' AND p.price >= ?';
      params.push(minPrice);
    }

    if (maxPrice) {
      query += ' AND p.price <= ?';
      params.push(maxPrice);
    }

    // Sorting
    switch (sort) {
      case 'price_low':
        query += ' ORDER BY p.price ASC';
        break;
      case 'price_high':
        query += ' ORDER BY p.price DESC';
        break;
      case 'popular':
        query += ' ORDER BY p.sold_count DESC';
        break;
      case 'rating':
        query += ' ORDER BY avg_rating DESC';
        break;
      default:
        query += ' ORDER BY p.created_at DESC';
    }

    // Pagination
    const offset = (page - 1) * limit;
    query += ' LIMIT ? OFFSET ?';
    params.push(parseInt(limit), parseInt(offset));

    const [products] = await pool.query(query, params);

    // Get total count
    let countQuery = 'SELECT COUNT(*) as total FROM products p WHERE p.status = "active"';
    const countParams = [];

    if (category) {
      countQuery += ' AND p.category_id = ?';
      countParams.push(category);
    }

    if (search) {
      countQuery += ' AND (p.title LIKE ? OR p.description LIKE ?)';
      countParams.push(`%${search}%`, `%${search}%`);
    }

    const [countResult] = await pool.query(countQuery, countParams);
    const total = countResult[0].total;

    res.json({
      success: true,
      data: {
        products,
        pagination: {
          page: parseInt(page),
          limit: parseInt(limit),
          total,
          totalPages: Math.ceil(total / limit)
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

// Get product by ID
export const getProductById = async (req, res) => {
  try {
    const { id } = req.params;

    const [products] = await pool.query(`
      SELECT p.*, c.name as category_name, c.slug as category_slug,
      u.id as seller_id, u.name as seller_name, u.seller_level, u.avatar as seller_avatar,
      (SELECT AVG(rating) FROM reviews WHERE product_id = p.id) as avg_rating,
      (SELECT COUNT(*) FROM reviews WHERE product_id = p.id) as review_count
      FROM products p
      JOIN categories c ON p.category_id = c.id
      JOIN users u ON p.seller_id = u.id
      WHERE p.id = ?
    `, [id]);

    if (products.length === 0) {
      return res.status(404).json({ 
        success: false, 
        message: 'Product not found' 
      });
    }

    // Get reviews
    const [reviews] = await pool.query(`
      SELECT r.*, u.name as buyer_name, u.avatar as buyer_avatar
      FROM reviews r
      JOIN users u ON r.buyer_id = u.id
      WHERE r.product_id = ?
      ORDER BY r.created_at DESC
      LIMIT 10
    `, [id]);

    res.json({
      success: true,
      data: {
        ...products[0],
        reviews
      }
    });
  } catch (error) {
    console.error('Get product error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to get product', 
      error: error.message 
    });
  }
};

// Create product (Seller only)
export const createProduct = async (req, res) => {
  try {
    const {
      title,
      description,
      price,
      category_id,
      stock,
      auto_delivery,
      delivery_content,
      images
    } = req.body;

    const [result] = await pool.query(`
      INSERT INTO products 
      (seller_id, title, description, price, category_id, stock, auto_delivery, delivery_content, images, status)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    `, [
      req.user.id,
      title,
      description,
      price,
      category_id,
      stock,
      auto_delivery || false,
      delivery_content || null,
      JSON.stringify(images || [])
    ]);

    res.status(201).json({
      success: true,
      message: 'Product created successfully. Waiting for admin approval.',
      data: { productId: result.insertId }
    });
  } catch (error) {
    console.error('Create product error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to create product', 
      error: error.message 
    });
  }
};

// Update product (Seller only, own products)
export const updateProduct = async (req, res) => {
  try {
    const { id } = req.params;
    const {
      title,
      description,
      price,
      category_id,
      stock,
      auto_delivery,
      delivery_content,
      images,
      status
    } = req.body;

    // Check ownership
    const [products] = await pool.query(
      'SELECT seller_id FROM products WHERE id = ?',
      [id]
    );

    if (products.length === 0) {
      return res.status(404).json({ 
        success: false, 
        message: 'Product not found' 
      });
    }

    if (products[0].seller_id !== req.user.id && req.user.role !== 'admin') {
      return res.status(403).json({ 
        success: false, 
        message: 'Access denied' 
      });
    }

    const updates = [];
    const values = [];

    if (title) {
      updates.push('title = ?');
      values.push(title);
    }

    if (description) {
      updates.push('description = ?');
      values.push(description);
    }

    if (price) {
      updates.push('price = ?');
      values.push(price);
    }

    if (category_id) {
      updates.push('category_id = ?');
      values.push(category_id);
    }

    if (stock !== undefined) {
      updates.push('stock = ?');
      values.push(stock);
    }

    if (auto_delivery !== undefined) {
      updates.push('auto_delivery = ?');
      values.push(auto_delivery);
    }

    if (delivery_content) {
      updates.push('delivery_content = ?');
      values.push(delivery_content);
    }

    if (images) {
      updates.push('images = ?');
      values.push(JSON.stringify(images));
    }

    if (status && req.user.role === 'admin') {
      updates.push('status = ?');
      values.push(status);
    }

    if (updates.length === 0) {
      return res.status(400).json({ 
        success: false, 
        message: 'No fields to update' 
      });
    }

    values.push(id);

    await pool.query(
      `UPDATE products SET ${updates.join(', ')} WHERE id = ?`,
      values
    );

    res.json({
      success: true,
      message: 'Product updated successfully'
    });
  } catch (error) {
    console.error('Update product error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to update product', 
      error: error.message 
    });
  }
};

// Delete product (Seller only, own products)
export const deleteProduct = async (req, res) => {
  try {
    const { id } = req.params;

    const [products] = await pool.query(
      'SELECT seller_id FROM products WHERE id = ?',
      [id]
    );

    if (products.length === 0) {
      return res.status(404).json({ 
        success: false, 
        message: 'Product not found' 
      });
    }

    if (products[0].seller_id !== req.user.id && req.user.role !== 'admin') {
      return res.status(403).json({ 
        success: false, 
        message: 'Access denied' 
      });
    }

    await pool.query('DELETE FROM products WHERE id = ?', [id]);

    res.json({
      success: true,
      message: 'Product deleted successfully'
    });
  } catch (error) {
    console.error('Delete product error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to delete product', 
      error: error.message 
    });
  }
};

// Get seller products
export const getSellerProducts = async (req, res) => {
  try {
    const { page = 1, limit = 20 } = req.query;
    const offset = (page - 1) * limit;

    const [products] = await pool.query(`
      SELECT p.*, c.name as category_name,
      (SELECT AVG(rating) FROM reviews WHERE product_id = p.id) as avg_rating,
      (SELECT COUNT(*) FROM reviews WHERE product_id = p.id) as review_count
      FROM products p
      JOIN categories c ON p.category_id = c.id
      WHERE p.seller_id = ?
      ORDER BY p.created_at DESC
      LIMIT ? OFFSET ?
    `, [req.user.id, parseInt(limit), parseInt(offset)]);

    const [countResult] = await pool.query(
      'SELECT COUNT(*) as total FROM products WHERE seller_id = ?',
      [req.user.id]
    );

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
    console.error('Get seller products error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to get products', 
      error: error.message 
    });
  }
};

// Get categories
export const getCategories = async (req, res) => {
  try {
    const [categories] = await pool.query(`
      SELECT c.*, 
      (SELECT COUNT(*) FROM products WHERE category_id = c.id AND status = 'active') as product_count
      FROM categories c
      ORDER BY c.name ASC
    `);

    res.json({
      success: true,
      data: categories
    });
  } catch (error) {
    console.error('Get categories error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to get categories', 
      error: error.message 
    });
  }
};

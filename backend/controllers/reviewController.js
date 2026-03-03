import pool from '../config/database.js';

// Create review
export const createReview = async (req, res) => {
  try {
    const { order_id, rating, comment } = req.body;

    // Check if order exists and belongs to user
    const [orders] = await pool.query(`
      SELECT o.*, oi.product_id 
      FROM orders o
      JOIN order_items oi ON o.id = oi.order_id
      WHERE o.id = ? AND o.buyer_id = ? AND o.status = 'completed'
      LIMIT 1
    `, [order_id, req.user.id]);

    if (orders.length === 0) {
      return res.status(404).json({ 
        success: false, 
        message: 'Order not found or not completed' 
      });
    }

    const order = orders[0];

    // Check if review already exists
    const [existingReviews] = await pool.query(
      'SELECT id FROM reviews WHERE order_id = ?',
      [order_id]
    );

    if (existingReviews.length > 0) {
      return res.status(409).json({ 
        success: false, 
        message: 'Review already exists for this order' 
      });
    }

    // Create review
    const [result] = await pool.query(`
      INSERT INTO reviews 
      (order_id, product_id, buyer_id, seller_id, rating, comment)
      VALUES (?, ?, ?, ?, ?, ?)
    `, [
      order_id,
      order.product_id,
      req.user.id,
      order.seller_id,
      rating,
      comment || null
    ]);

    res.status(201).json({
      success: true,
      message: 'Review submitted successfully',
      data: { reviewId: result.insertId }
    });
  } catch (error) {
    console.error('Create review error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to create review', 
      error: error.message 
    });
  }
};

// Get product reviews
export const getProductReviews = async (req, res) => {
  try {
    const { productId } = req.params;
    const { page = 1, limit = 10 } = req.query;
    const offset = (page - 1) * limit;

    const [reviews] = await pool.query(`
      SELECT r.*, u.name as buyer_name, u.avatar as buyer_avatar
      FROM reviews r
      JOIN users u ON r.buyer_id = u.id
      WHERE r.product_id = ?
      ORDER BY r.created_at DESC
      LIMIT ? OFFSET ?
    `, [productId, parseInt(limit), parseInt(offset)]);

    const [countResult] = await pool.query(
      'SELECT COUNT(*) as total, AVG(rating) as avg_rating FROM reviews WHERE product_id = ?',
      [productId]
    );

    res.json({
      success: true,
      data: {
        reviews,
        stats: countResult[0],
        pagination: {
          page: parseInt(page),
          limit: parseInt(limit),
          total: countResult[0].total,
          totalPages: Math.ceil(countResult[0].total / limit)
        }
      }
    });
  } catch (error) {
    console.error('Get reviews error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to get reviews', 
      error: error.message 
    });
  }
};

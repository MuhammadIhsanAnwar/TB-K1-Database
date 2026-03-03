import express from 'express';
import { body } from 'express-validator';
import {
  createReview,
  getProductReviews
} from '../controllers/reviewController.js';
import { authMiddleware, roleMiddleware, verifiedMiddleware } from '../middleware/auth.js';
import { validate } from '../middleware/validate.js';

const router = express.Router();

// Create review (buyer only)
router.post('/',
  authMiddleware,
  roleMiddleware('buyer'),
  verifiedMiddleware,
  [
    body('order_id').isInt().withMessage('Valid order ID is required'),
    body('rating').isInt({ min: 1, max: 5 }).withMessage('Rating must be between 1 and 5'),
    body('comment').optional().trim()
  ],
  validate,
  createReview
);

// Get product reviews (public)
router.get('/product/:productId', getProductReviews);

export default router;

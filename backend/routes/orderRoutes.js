import express from 'express';
import { body } from 'express-validator';
import {
  createOrder,
  uploadPaymentProof,
  confirmOrder,
  processOrder,
  getBuyerOrders,
  getSellerOrders,
  getOrderById,
  cancelOrder
} from '../controllers/orderController.js';
import { authMiddleware, roleMiddleware, verifiedMiddleware } from '../middleware/auth.js';
import { validate } from '../middleware/validate.js';

const router = express.Router();

// Create order (buyer)
router.post('/',
  authMiddleware,
  verifiedMiddleware,
  [
    body('items').isArray({ min: 1 }).withMessage('Items are required'),
    body('items.*.product_id').isInt().withMessage('Valid product ID required'),
    body('items.*.quantity').isInt({ min: 1 }).withMessage('Quantity must be at least 1'),
    body('payment_method').isIn(['wallet', 'transfer', 'ewallet']).withMessage('Invalid payment method')
  ],
  validate,
  createOrder
);

// Get buyer orders
router.get('/buyer/my-orders', authMiddleware, roleMiddleware('buyer'), getBuyerOrders);

// Get seller orders
router.get('/seller/my-orders', authMiddleware, roleMiddleware('seller'), getSellerOrders);

// Get order by ID
router.get('/:id', authMiddleware, getOrderById);

// Upload payment proof (buyer)
router.put('/:id/payment-proof',
  authMiddleware,
  roleMiddleware('buyer'),
  [body('payment_proof').notEmpty().withMessage('Payment proof is required')],
  validate,
  uploadPaymentProof
);

// Process order (seller)
router.put('/:id/process', authMiddleware, roleMiddleware('seller'), processOrder);

// Confirm order (buyer)
router.put('/:id/confirm', authMiddleware, roleMiddleware('buyer'), confirmOrder);

// Cancel order (buyer)
router.put('/:id/cancel', authMiddleware, roleMiddleware('buyer'), cancelOrder);

export default router;

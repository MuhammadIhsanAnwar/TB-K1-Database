import express from 'express';
import { body } from 'express-validator';
import {
  getProducts,
  getProductById,
  createProduct,
  updateProduct,
  deleteProduct,
  getSellerProducts,
  getCategories
} from '../controllers/productController.js';
import { authMiddleware, optionalAuth, roleMiddleware } from '../middleware/auth.js';
import { validate } from '../middleware/validate.js';

const router = express.Router();

// Get categories
router.get('/categories', getCategories);

// Get all products (public)
router.get('/', optionalAuth, getProducts);

// Get product by ID (public)
router.get('/:id', optionalAuth, getProductById);

// Get seller's own products
router.get('/seller/my-products', authMiddleware, roleMiddleware('seller'), getSellerProducts);

// Create product (seller only)
router.post('/',
  authMiddleware,
  roleMiddleware('seller'),
  [
    body('title').trim().notEmpty().withMessage('Title is required'),
    body('description').trim().notEmpty().withMessage('Description is required'),
    body('price').isFloat({ min: 0 }).withMessage('Valid price is required'),
    body('category_id').isInt().withMessage('Category ID is required'),
    body('stock').isInt({ min: 0 }).withMessage('Stock must be a positive number'),
    body('auto_delivery').optional().isBoolean(),
    body('delivery_content').optional().trim(),
    body('images').optional().isArray()
  ],
  validate,
  createProduct
);

// Update product (seller only, own products)
router.put('/:id', authMiddleware, roleMiddleware('seller'), updateProduct);

// Delete product (seller only, own products)
router.delete('/:id', authMiddleware, roleMiddleware('seller'), deleteProduct);

export default router;

import express from 'express';
import { body } from 'express-validator';
import {
  getDashboardAnalytics,
  getAllUsers,
  toggleSuspendUser,
  verifySeller,
  getAllProducts,
  moderateProduct,
  getAllTransactions,
  getDisputes,
  resolveDispute
} from '../controllers/adminController.js';
import { authMiddleware, roleMiddleware } from '../middleware/auth.js';
import { validate } from '../middleware/validate.js';

const router = express.Router();

// All routes require admin role
router.use(authMiddleware, roleMiddleware('admin'));

// Dashboard analytics
router.get('/dashboard', getDashboardAnalytics);

// User management
router.get('/users', getAllUsers);
router.put('/users/:id/suspend',
  [
    body('suspend').isBoolean().withMessage('Suspend must be boolean'),
    body('reason').optional().trim()
  ],
  validate,
  toggleSuspendUser
);
router.put('/users/:id/verify-seller', verifySeller);

// Product management
router.get('/products', getAllProducts);
router.put('/products/:id/moderate',
  [
    body('status').isIn(['active', 'rejected', 'inactive']).withMessage('Invalid status'),
    body('reason').optional().trim()
  ],
  validate,
  moderateProduct
);

// Transaction management
router.get('/transactions', getAllTransactions);

// Dispute management
router.get('/disputes', getDisputes);
router.put('/disputes/:id/resolve',
  [body('resolution').notEmpty().withMessage('Resolution is required')],
  validate,
  resolveDispute
);

export default router;

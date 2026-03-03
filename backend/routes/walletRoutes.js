import express from 'express';
import { body } from 'express-validator';
import {
  getWallet,
  getWalletTransactions,
  requestDeposit,
  requestWithdrawal,
  approveDeposit,
  rejectTransaction,
  getPendingTransactions
} from '../controllers/walletController.js';
import { authMiddleware, roleMiddleware, verifiedMiddleware } from '../middleware/auth.js';
import { validate } from '../middleware/validate.js';

const router = express.Router();

// Get wallet
router.get('/', authMiddleware, getWallet);

// Get wallet transactions
router.get('/transactions', authMiddleware, getWalletTransactions);

// Request deposit
router.post('/deposit',
  authMiddleware,
  verifiedMiddleware,
  [
    body('amount').isFloat({ min: 10000 }).withMessage('Minimum deposit is Rp 10,000'),
    body('payment_proof').optional().trim()
  ],
  validate,
  requestDeposit
);

// Request withdrawal
router.post('/withdraw',
  authMiddleware,
  verifiedMiddleware,
  [
    body('amount').isFloat({ min: 50000 }).withMessage('Minimum withdrawal is Rp 50,000'),
    body('bank_name').notEmpty().withMessage('Bank name is required'),
    body('account_number').notEmpty().withMessage('Account number is required'),
    body('account_name').notEmpty().withMessage('Account name is required')
  ],
  validate,
  requestWithdrawal
);

// Admin: Get pending transactions
router.get('/admin/pending', authMiddleware, roleMiddleware('admin'), getPendingTransactions);

// Admin: Approve deposit
router.put('/admin/approve/:id', authMiddleware, roleMiddleware('admin'), approveDeposit);

// Admin: Reject transaction
router.put('/admin/reject/:id',
  authMiddleware,
  roleMiddleware('admin'),
  [body('reason').optional().trim()],
  validate,
  rejectTransaction
);

export default router;

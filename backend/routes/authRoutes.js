import express from 'express';
import { body } from 'express-validator';
import {
  register,
  verifyEmail,
  login,
  refreshToken,
  logout,
  requestPasswordReset,
  resetPassword,
  getCurrentUser,
  updateProfile,
  deleteAccount
} from '../controllers/authController.js';
import { authMiddleware } from '../middleware/auth.js';
import { validate } from '../middleware/validate.js';
import { authLimiter } from '../middleware/rateLimiter.js';

const router = express.Router();

// Register
router.post('/register', 
  authLimiter,
  [
    body('name').trim().notEmpty().withMessage('Name is required'),
    body('email').isEmail().withMessage('Valid email is required'),
    body('password').isLength({ min: 6 }).withMessage('Password must be at least 6 characters'),
    body('role').optional().isIn(['buyer', 'seller']).withMessage('Invalid role')
  ],
  validate,
  register
);

// Verify email
router.post('/verify-email',
  [body('token').notEmpty().withMessage('Token is required')],
  validate,
  verifyEmail
);

// Login
router.post('/login',
  authLimiter,
  [
    body('email').isEmail().withMessage('Valid email is required'),
    body('password').notEmpty().withMessage('Password is required')
  ],
  validate,
  login
);

// Refresh token
router.post('/refresh', refreshToken);

// Logout
router.post('/logout', authMiddleware, logout);

// Request password reset
router.post('/forgot-password',
  authLimiter,
  [body('email').isEmail().withMessage('Valid email is required')],
  validate,
  requestPasswordReset
);

// Reset password
router.post('/reset-password',
  [
    body('token').notEmpty().withMessage('Token is required'),
    body('newPassword').isLength({ min: 6 }).withMessage('Password must be at least 6 characters')
  ],
  validate,
  resetPassword
);

// Get current user
router.get('/me', authMiddleware, getCurrentUser);

// Update profile
router.put('/profile',
  authMiddleware,
  [
    body('name').optional().trim().notEmpty(),
    body('phone').optional().trim()
  ],
  validate,
  updateProfile
);

// Delete account
router.delete('/account',
  authMiddleware,
  [body('password').notEmpty().withMessage('Password required for confirmation')],
  validate,
  deleteAccount
);

export default router;

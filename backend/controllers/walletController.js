import pool from '../config/database.js';

// Get wallet
export const getWallet = async (req, res) => {
  try {
    const [wallets] = await pool.query(
      'SELECT * FROM wallets WHERE user_id = ?',
      [req.user.id]
    );

    if (wallets.length === 0) {
      // Create wallet if not exists
      const [result] = await pool.query(
        'INSERT INTO wallets (user_id, balance) VALUES (?, 0.00)',
        [req.user.id]
      );

      return res.json({
        success: true,
        data: {
          id: result.insertId,
          user_id: req.user.id,
          balance: 0.00
        }
      });
    }

    res.json({
      success: true,
      data: wallets[0]
    });
  } catch (error) {
    console.error('Get wallet error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to get wallet', 
      error: error.message 
    });
  }
};

// Get wallet transactions
export const getWalletTransactions = async (req, res) => {
  try {
    const { type, page = 1, limit = 20 } = req.query;
    const offset = (page - 1) * limit;

    let query = `
      SELECT * FROM wallet_transactions
      WHERE user_id = ?
    `;

    const params = [req.user.id];

    if (type) {
      query += ' AND type = ?';
      params.push(type);
    }

    query += ' ORDER BY created_at DESC LIMIT ? OFFSET ?';
    params.push(parseInt(limit), parseInt(offset));

    const [transactions] = await pool.query(query, params);

    const [countResult] = await pool.query(
      'SELECT COUNT(*) as total FROM wallet_transactions WHERE user_id = ?',
      [req.user.id]
    );

    res.json({
      success: true,
      data: {
        transactions,
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

// Request deposit
export const requestDeposit = async (req, res) => {
  const connection = await pool.getConnection();
  
  try {
    const { amount, payment_proof } = req.body;

    if (amount < 10000) {
      return res.status(400).json({ 
        success: false, 
        message: 'Minimum deposit is Rp 10,000' 
      });
    }

    await connection.beginTransaction();

    const [wallets] = await connection.query(
      'SELECT * FROM wallets WHERE user_id = ?',
      [req.user.id]
    );

    if (wallets.length === 0) {
      await connection.rollback();
      return res.status(404).json({ 
        success: false, 
        message: 'Wallet not found' 
      });
    }

    const wallet = wallets[0];
    const balanceBefore = parseFloat(wallet.balance);

    // Create pending transaction
    const [result] = await connection.query(`
      INSERT INTO wallet_transactions 
      (wallet_id, user_id, type, amount, balance_before, balance_after, status, description)
      VALUES (?, ?, 'deposit', ?, ?, ?, 'pending', ?)
    `, [
      wallet.id,
      req.user.id,
      amount,
      balanceBefore,
      balanceBefore, // Will be updated when approved
      payment_proof || 'Deposit request'
    ]);

    await connection.commit();

    res.status(201).json({
      success: true,
      message: 'Deposit request submitted. Waiting for admin approval.',
      data: { transactionId: result.insertId }
    });
  } catch (error) {
    await connection.rollback();
    console.error('Request deposit error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to request deposit', 
      error: error.message 
    });
  } finally {
    connection.release();
  }
};

// Request withdrawal
export const requestWithdrawal = async (req, res) => {
  const connection = await pool.getConnection();
  
  try {
    const { amount, bank_name, account_number, account_name } = req.body;

    if (amount < 50000) {
      return res.status(400).json({ 
        success: false, 
        message: 'Minimum withdrawal is Rp 50,000' 
      });
    }

    await connection.beginTransaction();

    const [wallets] = await connection.query(
      'SELECT * FROM wallets WHERE user_id = ?',
      [req.user.id]
    );

    if (wallets.length === 0) {
      await connection.rollback();
      return res.status(404).json({ 
        success: false, 
        message: 'Wallet not found' 
      });
    }

    const wallet = wallets[0];
    const balanceBefore = parseFloat(wallet.balance);

    if (balanceBefore < amount) {
      await connection.rollback();
      return res.status(400).json({ 
        success: false, 
        message: 'Insufficient balance' 
      });
    }

    const balanceAfter = balanceBefore - amount;

    // Deduct balance immediately
    await connection.query(
      'UPDATE wallets SET balance = ? WHERE id = ?',
      [balanceAfter, wallet.id]
    );

    // Create pending withdrawal transaction
    const description = `Withdrawal to ${bank_name} - ${account_number} (${account_name})`;
    
    const [result] = await connection.query(`
      INSERT INTO wallet_transactions 
      (wallet_id, user_id, type, amount, balance_before, balance_after, status, description)
      VALUES (?, ?, 'withdraw', ?, ?, ?, 'pending', ?)
    `, [
      wallet.id,
      req.user.id,
      amount,
      balanceBefore,
      balanceAfter,
      description
    ]);

    await connection.commit();

    res.status(201).json({
      success: true,
      message: 'Withdrawal request submitted. Processing may take 1-3 business days.',
      data: { transactionId: result.insertId }
    });
  } catch (error) {
    await connection.rollback();
    console.error('Request withdrawal error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to request withdrawal', 
      error: error.message 
    });
  } finally {
    connection.release();
  }
};

// Approve deposit (Admin only)
export const approveDeposit = async (req, res) => {
  const connection = await pool.getConnection();
  
  try {
    const { id } = req.params;

    await connection.beginTransaction();

    const [transactions] = await connection.query(
      'SELECT * FROM wallet_transactions WHERE id = ? AND type = "deposit" AND status = "pending"',
      [id]
    );

    if (transactions.length === 0) {
      await connection.rollback();
      return res.status(404).json({ 
        success: false, 
        message: 'Transaction not found or already processed' 
      });
    }

    const transaction = transactions[0];
    const newBalance = parseFloat(transaction.balance_before) + parseFloat(transaction.amount);

    // Update wallet balance
    await connection.query(
      'UPDATE wallets SET balance = ? WHERE id = ?',
      [newBalance, transaction.wallet_id]
    );

    // Update transaction status
    await connection.query(
      'UPDATE wallet_transactions SET status = "completed", balance_after = ? WHERE id = ?',
      [newBalance, id]
    );

    await connection.commit();

    res.json({
      success: true,
      message: 'Deposit approved successfully'
    });
  } catch (error) {
    await connection.rollback();
    console.error('Approve deposit error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to approve deposit', 
      error: error.message 
    });
  } finally {
    connection.release();
  }
};

// Reject transaction (Admin only)
export const rejectTransaction = async (req, res) => {
  const connection = await pool.getConnection();
  
  try {
    const { id } = req.params;
    const { reason } = req.body;

    await connection.beginTransaction();

    const [transactions] = await connection.query(
      'SELECT * FROM wallet_transactions WHERE id = ? AND status = "pending"',
      [id]
    );

    if (transactions.length === 0) {
      await connection.rollback();
      return res.status(404).json({ 
        success: false, 
        message: 'Transaction not found or already processed' 
      });
    }

    const transaction = transactions[0];

    // If withdrawal, refund to wallet
    if (transaction.type === 'withdraw') {
      const refundAmount = parseFloat(transaction.balance_before);
      
      await connection.query(
        'UPDATE wallets SET balance = ? WHERE id = ?',
        [refundAmount, transaction.wallet_id]
      );
    }

    // Update transaction status
    await connection.query(
      'UPDATE wallet_transactions SET status = "rejected", description = ? WHERE id = ?',
      [reason || 'Rejected by admin', id]
    );

    await connection.commit();

    res.json({
      success: true,
      message: 'Transaction rejected'
    });
  } catch (error) {
    await connection.rollback();
    console.error('Reject transaction error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to reject transaction', 
      error: error.message 
    });
  } finally {
    connection.release();
  }
};

// Get pending transactions (Admin only)
export const getPendingTransactions = async (req, res) => {
  try {
    const { page = 1, limit = 20 } = req.query;
    const offset = (page - 1) * limit;

    const [transactions] = await pool.query(`
      SELECT wt.*, u.name as user_name, u.email as user_email
      FROM wallet_transactions wt
      JOIN users u ON wt.user_id = u.id
      WHERE wt.status = 'pending'
      ORDER BY wt.created_at DESC
      LIMIT ? OFFSET ?
    `, [parseInt(limit), parseInt(offset)]);

    const [countResult] = await pool.query(
      'SELECT COUNT(*) as total FROM wallet_transactions WHERE status = "pending"'
    );

    res.json({
      success: true,
      data: {
        transactions,
        pagination: {
          page: parseInt(page),
          limit: parseInt(limit),
          total: countResult[0].total,
          totalPages: Math.ceil(countResult[0].total / limit)
        }
      }
    });
  } catch (error) {
    console.error('Get pending transactions error:', error);
    res.status(500).json({ 
      success: false, 
      message: 'Failed to get transactions', 
      error: error.message 
    });
  }
};

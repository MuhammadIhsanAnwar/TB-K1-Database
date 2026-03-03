import jwt from 'jsonwebtoken';
import pool from '../config/database.js';

export const handleChatSocket = (io) => {
  // Store online users
  const onlineUsers = new Map();

  io.on('connection', (socket) => {
    console.log('🔌 Client connected:', socket.id);

    // Authenticate user
    socket.on('authenticate', async (token) => {
      try {
        const decoded = jwt.verify(token, process.env.JWT_SECRET);
        
        const [users] = await pool.query(
          'SELECT id, name, email, role FROM users WHERE id = ?',
          [decoded.userId]
        );

        if (users.length > 0) {
          socket.userId = users[0].id;
          socket.userData = users[0];
          onlineUsers.set(users[0].id, socket.id);
          
          socket.emit('authenticated', { 
            success: true, 
            user: users[0] 
          });

          // Notify others
          socket.broadcast.emit('user_online', { userId: users[0].id });

          console.log('✅ User authenticated:', users[0].name);
        } else {
          socket.emit('authenticated', { 
            success: false, 
            message: 'User not found' 
          });
        }
      } catch (error) {
        socket.emit('authenticated', { 
          success: false, 
          message: 'Invalid token' 
        });
      }
    });

    // Send message
    socket.on('send_message', async ({ receiverId, message }) => {
      try {
        if (!socket.userId) {
          socket.emit('error', { message: 'Not authenticated' });
          return;
        }

        // Save message to database
        const [result] = await pool.query(
          'INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)',
          [socket.userId, receiverId, message]
        );

        const messageData = {
          id: result.insertId,
          sender_id: socket.userId,
          receiver_id: receiverId,
          message,
          sender_name: socket.userData.name,
          created_at: new Date(),
          is_read: false
        };

        // Send to receiver if online
        const receiverSocketId = onlineUsers.get(parseInt(receiverId));
        if (receiverSocketId) {
          io.to(receiverSocketId).emit('new_message', messageData);
        }

        // Confirm to sender
        socket.emit('message_sent', messageData);

        console.log(`💬 Message from ${socket.userData.name} to user ${receiverId}`);
      } catch (error) {
        console.error('Message error:', error);
        socket.emit('error', { message: 'Failed to send message' });
      }
    });

    // Get conversation history
    socket.on('get_messages', async ({ otherUserId, page = 1, limit = 50 }) => {
      try {
        if (!socket.userId) {
          socket.emit('error', { message: 'Not authenticated' });
          return;
        }

        const offset = (page - 1) * limit;

        const [messages] = await pool.query(`
          SELECT m.*, 
            sender.name as sender_name,
            receiver.name as receiver_name
          FROM messages m
          JOIN users sender ON m.sender_id = sender.id
          JOIN users receiver ON m.receiver_id = receiver.id
          WHERE (m.sender_id = ? AND m.receiver_id = ?) 
             OR (m.sender_id = ? AND m.receiver_id = ?)
          ORDER BY m.created_at DESC
          LIMIT ? OFFSET ?
        `, [socket.userId, otherUserId, otherUserId, socket.userId, limit, offset]);

        socket.emit('messages_loaded', { 
          messages: messages.reverse(), 
          page,
          hasMore: messages.length === limit 
        });

        // Mark messages as read
        await pool.query(
          'UPDATE messages SET is_read = TRUE WHERE sender_id = ? AND receiver_id = ? AND is_read = FALSE',
          [otherUserId, socket.userId]
        );
      } catch (error) {
        console.error('Get messages error:', error);
        socket.emit('error', { message: 'Failed to load messages' });
      }
    });

    // Get conversations list
    socket.on('get_conversations', async () => {
      try {
        if (!socket.userId) {
          socket.emit('error', { message: 'Not authenticated' });
          return;
        }

        const [conversations] = await pool.query(`
          SELECT 
            CASE 
              WHEN m.sender_id = ? THEN m.receiver_id
              ELSE m.sender_id
            END as other_user_id,
            CASE 
              WHEN m.sender_id = ? THEN receiver.name
              ELSE sender.name
            END as other_user_name,
            CASE 
              WHEN m.sender_id = ? THEN receiver.avatar
              ELSE sender.avatar
            END as other_user_avatar,
            m.message as last_message,
            m.created_at as last_message_time,
            (SELECT COUNT(*) FROM messages 
             WHERE receiver_id = ? AND sender_id = other_user_id AND is_read = FALSE) as unread_count
          FROM messages m
          JOIN users sender ON m.sender_id = sender.id
          JOIN users receiver ON m.receiver_id = receiver.id
          WHERE m.sender_id = ? OR m.receiver_id = ?
          GROUP BY other_user_id
          ORDER BY m.created_at DESC
        `, [socket.userId, socket.userId, socket.userId, socket.userId, socket.userId, socket.userId]);

        socket.emit('conversations_loaded', { conversations });
      } catch (error) {
        console.error('Get conversations error:', error);
        socket.emit('error', { message: 'Failed to load conversations' });
      }
    });

    // Mark messages as read
    socket.on('mark_read', async ({ senderId }) => {
      try {
        if (!socket.userId) return;

        await pool.query(
          'UPDATE messages SET is_read = TRUE WHERE sender_id = ? AND receiver_id = ?',
          [senderId, socket.userId]
        );

        socket.emit('messages_marked_read', { senderId });
      } catch (error) {
        console.error('Mark read error:', error);
      }
    });

    // Typing indicator
    socket.on('typing', ({ receiverId }) => {
      const receiverSocketId = onlineUsers.get(parseInt(receiverId));
      if (receiverSocketId && socket.userId) {
        io.to(receiverSocketId).emit('user_typing', { 
          userId: socket.userId,
          name: socket.userData?.name 
        });
      }
    });

    socket.on('stop_typing', ({ receiverId }) => {
      const receiverSocketId = onlineUsers.get(parseInt(receiverId));
      if (receiverSocketId && socket.userId) {
        io.to(receiverSocketId).emit('user_stop_typing', { 
          userId: socket.userId 
        });
      }
    });

    // Disconnect
    socket.on('disconnect', () => {
      if (socket.userId) {
        onlineUsers.delete(socket.userId);
        socket.broadcast.emit('user_offline', { userId: socket.userId });
        console.log('👋 User disconnected:', socket.userData?.name);
      }
      console.log('🔌 Client disconnected:', socket.id);
    });
  });
};

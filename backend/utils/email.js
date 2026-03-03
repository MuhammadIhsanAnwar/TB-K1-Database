import nodemailer from 'nodemailer';
import dotenv from 'dotenv';

dotenv.config();

// Create transporter using the provided email configuration
const transporter = nodemailer.createTransport({
  host: process.env.EMAIL_HOST,
  port: parseInt(process.env.EMAIL_PORT),
  secure: process.env.EMAIL_SECURE === 'true',
  auth: {
    user: process.env.EMAIL_USER,
    pass: process.env.EMAIL_PASSWORD
  },
  tls: {
    rejectUnauthorized: false
  }
});

// Verify connection
transporter.verify((error, success) => {
  if (error) {
    console.error('❌ Email configuration error:', error);
  } else {
    console.log('✅ Email server ready');
  }
});

export const sendEmail = async ({ to, subject, html, text }) => {
  try {
    const mailOptions = {
      from: process.env.EMAIL_FROM,
      to,
      subject,
      html,
      text: text || ''
    };

    const info = await transporter.sendMail(mailOptions);
    console.log('📧 Email sent:', info.messageId);
    return { success: true, messageId: info.messageId };
  } catch (error) {
    console.error('❌ Email sending failed:', error);
    return { success: false, error: error.message };
  }
};

export const sendVerificationEmail = async (email, name, token) => {
  const verificationUrl = `${process.env.CLIENT_URL}/verify-email?token=${token}`;
  
  const html = `
    <!DOCTYPE html>
    <html>
    <head>
      <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .button { display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
      </style>
    </head>
    <body>
      <div class="container">
        <div class="header">
          <h1>🎮 Lapak Gaming</h1>
          <p>Digital Marketplace</p>
        </div>
        <div class="content">
          <h2>Halo, ${name}! 👋</h2>
          <p>Terima kasih telah mendaftar di Lapak Gaming. Untuk mengaktifkan akun Anda, silakan klik tombol di bawah ini:</p>
          <div style="text-align: center;">
            <a href="${verificationUrl}" class="button">Verifikasi Email</a>
          </div>
          <p>Atau salin link berikut ke browser Anda:</p>
          <p style="background: #eee; padding: 10px; border-radius: 5px; word-break: break-all;">${verificationUrl}</p>
          <p><strong>Link ini akan kadaluarsa dalam 24 jam.</strong></p>
          <p>Jika Anda tidak mendaftar akun ini, abaikan email ini.</p>
        </div>
        <div class="footer">
          <p>&copy; 2026 Lapak Gaming. All rights reserved.</p>
        </div>
      </div>
    </body>
    </html>
  `;

  return sendEmail({
    to: email,
    subject: 'Verifikasi Email Anda - Lapak Gaming',
    html
  });
};

export const sendPasswordResetEmail = async (email, name, token) => {
  const resetUrl = `${process.env.CLIENT_URL}/reset-password?token=${token}`;
  
  const html = `
    <!DOCTYPE html>
    <html>
    <head>
      <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .button { display: inline-block; padding: 12px 30px; background: #f5576c; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
      </style>
    </head>
    <body>
      <div class="container">
        <div class="header">
          <h1>🔐 Reset Password</h1>
        </div>
        <div class="content">
          <h2>Halo, ${name}!</h2>
          <p>Kami menerima permintaan untuk mereset password akun Anda. Klik tombol di bawah untuk melanjutkan:</p>
          <div style="text-align: center;">
            <a href="${resetUrl}" class="button">Reset Password</a>
          </div>
          <p>Atau salin link berikut:</p>
          <p style="background: #eee; padding: 10px; border-radius: 5px; word-break: break-all;">${resetUrl}</p>
          <div class="warning">
            <p><strong>⚠️ Penting:</strong></p>
            <ul>
              <li>Link ini akan kadaluarsa dalam 1 jam</li>
              <li>Jika Anda tidak meminta reset password, abaikan email ini</li>
              <li>Password Anda tidak akan berubah sampai Anda mengklik link di atas</li>
            </ul>
          </div>
        </div>
        <div class="footer">
          <p>&copy; 2026 Lapak Gaming. All rights reserved.</p>
        </div>
      </div>
    </body>
    </html>
  `;

  return sendEmail({
    to: email,
    subject: 'Reset Password - Lapak Gaming',
    html
  });
};

export const sendOrderNotification = async (email, name, orderNumber, totalPrice) => {
  const html = `
    <!DOCTYPE html>
    <html>
    <head>
      <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .order-box { background: white; padding: 20px; border-radius: 10px; margin: 20px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
      </style>
    </head>
    <body>
      <div class="container">
        <div class="header">
          <h1>✅ Pesanan Diterima</h1>
        </div>
        <div class="content">
          <h2>Terima kasih, ${name}!</h2>
          <p>Pesanan Anda telah kami terima dan sedang diproses.</p>
          <div class="order-box">
            <h3>Detail Pesanan</h3>
            <p><strong>Nomor Pesanan:</strong> ${orderNumber}</p>
            <p><strong>Total Pembayaran:</strong> Rp ${parseInt(totalPrice).toLocaleString('id-ID')}</p>
            <p><strong>Status:</strong> Menunggu Pembayaran</p>
          </div>
          <p>Silakan lakukan pembayaran dan upload bukti transfer untuk melanjutkan pesanan Anda.</p>
        </div>
        <div class="footer">
          <p>&copy; 2026 Lapak Gaming. All rights reserved.</p>
        </div>
      </div>
    </body>
    </html>
  `;

  return sendEmail({
    to: email,
    subject: `Pesanan ${orderNumber} - Lapak Gaming`,
    html
  });
};

export default transporter;

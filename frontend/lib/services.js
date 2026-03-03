import api from './api';

export const authService = {
  register: async (data) => {
    const response = await api.post('/auth/register', data);
    return response.data;
  },

  verifyEmail: async (token) => {
    const response = await api.post('/auth/verify-email', { token });
    return response.data;
  },

  login: async (email, password) => {
    const response = await api.post('/auth/login', { email, password });
    return response.data;
  },

  logout: async () => {
    const response = await api.post('/auth/logout');
    return response.data;
  },

  getCurrentUser: async () => {
    const response = await api.get('/auth/me');
    return response.data;
  },

  updateProfile: async (data) => {
    const response = await api.put('/auth/profile', data);
    return response.data;
  },

  deleteAccount: async (password) => {
    const response = await api.delete('/auth/account', { data: { password } });
    return response.data;
  },

  forgotPassword: async (email) => {
    const response = await api.post('/auth/forgot-password', { email });
    return response.data;
  },

  resetPassword: async (token, newPassword) => {
    const response = await api.post('/auth/reset-password', { token, newPassword });
    return response.data;
  },
};

export const productService = {
  getProducts: async (filters = {}) => {
    const params = new URLSearchParams(filters).toString();
    const response = await api.get(`/products?${params}`);
    return response.data;
  },

  getProductById: async (id) => {
    const response = await api.get(`/products/${id}`);
    return response.data;
  },

  getCategories: async () => {
    const response = await api.get('/products/categories');
    return response.data;
  },

  createProduct: async (data) => {
    const response = await api.post('/products', data);
    return response.data;
  },

  updateProduct: async (id, data) => {
    const response = await api.put(`/products/${id}`, data);
    return response.data;
  },

  deleteProduct: async (id) => {
    const response = await api.delete(`/products/${id}`);
    return response.data;
  },

  getSellerProducts: async (page = 1) => {
    const response = await api.get(`/products/seller/my-products?page=${page}`);
    return response.data;
  },
};

export const orderService = {
  createOrder: async (data) => {
    const response = await api.post('/orders', data);
    return response.data;
  },

  getBuyerOrders: async (filters = {}) => {
    const params = new URLSearchParams(filters).toString();
    const response = await api.get(`/orders/buyer/my-orders?${params}`);
    return response.data;
  },

  getSellerOrders: async (filters = {}) => {
    const params = new URLSearchParams(filters).toString();
    const response = await api.get(`/orders/seller/my-orders?${params}`);
    return response.data;
  },

  getOrderById: async (id) => {
    const response = await api.get(`/orders/${id}`);
    return response.data;
  },

  uploadPaymentProof: async (id, paymentProof) => {
    const response = await api.put(`/orders/${id}/payment-proof`, { payment_proof: paymentProof });
    return response.data;
  },

  processOrder: async (id) => {
    const response = await api.put(`/orders/${id}/process`);
    return response.data;
  },

  confirmOrder: async (id) => {
    const response = await api.put(`/orders/${id}/confirm`);
    return response.data;
  },

  cancelOrder: async (id, reason) => {
    const response = await api.put(`/orders/${id}/cancel`, { reason });
    return response.data;
  },
};

export const walletService = {
  getWallet: async () => {
    const response = await api.get('/wallet');
    return response.data;
  },

  getTransactions: async (filters = {}) => {
    const params = new URLSearchParams(filters).toString();
    const response = await api.get(`/wallet/transactions?${params}`);
    return response.data;
  },

  requestDeposit: async (amount, paymentProof) => {
    const response = await api.post('/wallet/deposit', { amount, payment_proof: paymentProof });
    return response.data;
  },

  requestWithdrawal: async (data) => {
    const response = await api.post('/wallet/withdraw', data);
    return response.data;
  },
};

export const reviewService = {
  createReview: async (data) => {
    const response = await api.post('/reviews', data);
    return response.data;
  },

  getProductReviews: async (productId, page = 1) => {
    const response = await api.get(`/reviews/product/${productId}?page=${page}`);
    return response.data;
  },
};

export const adminService = {
  getDashboard: async () => {
    const response = await api.get('/admin/dashboard');
    return response.data;
  },

  getUsers: async (filters = {}) => {
    const params = new URLSearchParams(filters).toString();
    const response = await api.get(`/admin/users?${params}`);
    return response.data;
  },

  suspendUser: async (id, suspend, reason) => {
    const response = await api.put(`/admin/users/${id}/suspend`, { suspend, reason });
    return response.data;
  },

  verifySeller: async (id) => {
    const response = await api.put(`/admin/users/${id}/verify-seller`);
    return response.data;
  },

  getProducts: async (filters = {}) => {
    const params = new URLSearchParams(filters).toString();
    const response = await api.get(`/admin/products?${params}`);
    return response.data;
  },

  moderateProduct: async (id, status, reason) => {
    const response = await api.put(`/admin/products/${id}/moderate`, { status, reason });
    return response.data;
  },

  getTransactions: async (filters = {}) => {
    const params = new URLSearchParams(filters).toString();
    const response = await api.get(`/admin/transactions?${params}`);
    return response.data;
  },

  getPendingTransactions: async (page = 1) => {
    const response = await api.get(`/wallet/admin/pending?page=${page}`);
    return response.data;
  },

  approveDeposit: async (id) => {
    const response = await api.put(`/wallet/admin/approve/${id}`);
    return response.data;
  },

  rejectTransaction: async (id, reason) => {
    const response = await api.put(`/wallet/admin/reject/${id}`, { reason });
    return response.data;
  },

  getDisputes: async (filters = {}) => {
    const params = new URLSearchParams(filters).toString();
    const response = await api.get(`/admin/disputes?${params}`);
    return response.data;
  },

  resolveDispute: async (id, resolution) => {
    const response = await api.put(`/admin/disputes/${id}/resolve`, { resolution });
    return response.data;
  },
};

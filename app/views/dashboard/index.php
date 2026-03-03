<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Lapak Gaming</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="text-2xl font-bold text-indigo-600">
                    <i class="fas fa-gamepad"></i> Lapak Gaming
                </a>
                <div class="flex items-center space-x-4">
                    <a href="/dashboard" class="text-indigo-600 font-semibold">Dashboard</a>
                    <button id="logoutBtn" class="text-red-600 hover:text-red-700">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="flex gap-8">
            <!-- Sidebar -->
            <aside class="w-64">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-center mb-4 pb-4 border-b">
                        <img id="userAvatar" class="w-20 h-20 rounded-full mx-auto mb-2" src="">
                        <h3 id="userName" class="font-bold"></h3>
                        <p id="userEmail" class="text-sm text-gray-600"></p>
                        <span id="userRole" class="inline-block mt-2 px-3 py-1 bg-indigo-100 text-indigo-600 rounded-full text-xs"></span>
                    </div>
                    
                    <nav class="space-y-2">
                        <a href="/dashboard" class="block px-4 py-2 bg-indigo-100 text-indigo-600 rounded">
                            <i class="fas fa-home mr-2"></i>Dashboard
                        </a>
                        <a href="/orders" class="block px-4 py-2 hover:bg-gray-100 rounded">
                            <i class="fas fa-shopping-bag mr-2"></i>My Orders
                        </a>
                        <a href="/wallet" class="block px-4 py-2 hover:bg-gray-100 rounded">
                            <i class="fas fa-wallet mr-2"></i>Wallet
                        </a>
                        <div id="sellerMenu" class="hidden">
                            <a href="/seller/products" class="block px-4 py-2 hover:bg-gray-100 rounded">
                                <i class="fas fa-box mr-2"></i>My Products
                            </a>
                            <a href="/seller/orders" class="block px-4 py-2 hover:bg-gray-100 rounded">
                                <i class="fas fa-receipt mr-2"></i>Sales Orders
                            </a>
                        </div>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1">
                <div id="dashboardContent">
                    <div class="text-center py-12">
                        <i class="fas fa-spinner fa-spin text-4xl text-indigo-600"></i>
                        <p class="mt-4 text-gray-600">Loading dashboard...</p>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        const API_BASE = window.location.origin + '/api';
        const token = localStorage.getItem('access_token');
        
        if (!token) {
            window.location.href = '/login';
        }

        async function loadDashboard() {
            try {
                // Load user profile
                const profileRes = await fetch(API_BASE + '/user/profile', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const profileData = await profileRes.json();
                const user = profileData.data.user;
                const wallet = profileData.data.wallet;
                
                // Update user info
                document.getElementById('userAvatar').src = user.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.username)}`;
                document.getElementById('userName').textContent = user.username;
                document.getElementById('userEmail').textContent = user.email;
                document.getElementById('userRole').textContent = user.role.toUpperCase();
                
                // Show seller menu if user is seller
                if (user.role === 'seller') {
                    document.getElementById('sellerMenu').classList.remove('hidden');
                }
                
                // Load appropriate dashboard
                if (user.role === 'admin') {
                    loadAdminDashboard();
                } else if (user.role === 'seller') {
                    loadSellerDashboard();
                } else {
                    loadBuyerDashboard();
                }
                
            } catch (error) {
                console.error('Failed to load dashboard:', error);
                localStorage.removeItem('access_token');
                window.location.href = '/login';
            }
        }

        async function loadBuyerDashboard() {
            try {
                const res = await fetch(API_BASE + '/dashboard/buyer', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await res.json();
                const stats = data.data.stats;
                const wallet = data.data.wallet;
                
                document.getElementById('dashboardContent').innerHTML = `
                    <h1 class="text-3xl font-bold mb-8">Buyer Dashboard</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm">Wallet Balance</p>
                                    <p class="text-2xl font-bold text-green-600">Rp ${parseInt(wallet.balance).toLocaleString('id-ID')}</p>
                                </div>
                                <i class="fas fa-wallet text-3xl text-green-600"></i>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6"> 
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm">Total Orders</p>
                                    <p class="text-2xl font-bold">${stats.total_orders}</p>
                                </div>
                                <i class="fas fa-shopping-cart text-3xl text-indigo-600"></i>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm">Completed</p>
                                    <p class="text-2xl font-bold text-green-600">${stats.completed_orders}</p>
                                </div>
                                <i class="fas fa-check-circle text-3xl text-green-600"></i>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm">Total Spent</p>
                                    <p class="text-2xl font-bold text-red-600">Rp ${parseInt(stats.total_spent).toLocaleString('id-ID')}</p>
                                </div>
                                <i class="fas fa-money-bill-wave text-3xl text-red-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">Recent Orders</h2>
                        <div id="recentOrders">Loading...</div>
                    </div>
                `;
                
                loadRecentOrders();
            } catch (error) {
                console.error('Failed to load buyer dashboard:', error);
            }
        }

        async function loadSellerDashboard() {
            try {
                const res = await fetch(API_BASE + '/dashboard/seller', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await res.json();
                const stats = data.data.stats;
                const wallet = data.data.wallet;
                const seller = data.data.seller;
                
                document.getElementById('dashboardContent').innerHTML = `
                    <h1 class="text-3xl font-bold mb-8">Seller Dashboard</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm">Available Balance</p>
                                    <p class="text-2xl font-bold text-green-600">Rp ${parseInt(stats.available_balance).toLocaleString('id-ID')}</p>
                                    <p class="text-xs text-gray-500 mt-1">Pending: Rp ${parseInt(stats.pending_balance).toLocaleString('id-ID')}</p>
                                </div>
                                <i class="fas fa-wallet text-3xl text-green-600"></i>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm">Total Revenue</p>
                                    <p class="text-2xl font-bold text-indigo-600">Rp ${parseInt(stats.total_revenue).toLocaleString('id-ID')}</p>
                                </div>
                                <i class="fas fa-chart-line text-3xl text-indigo-600"></i>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm">Total Products</p>
                                    <p class="text-2xl font-bold">${stats.total_products}</p>
                                    <p class="text-xs text-gray-500 mt-1">Active: ${stats.active_products}</p>
                                </div>
                                <i class="fas fa-box text-3xl text-purple-600"></i>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm">Total Orders</p>
                                    <p class="text-2xl font-bold">${stats.total_orders}</p>
                                    <p class="text-xs text-gray-500 mt-1">Completed: ${stats.completed_orders}</p>
                                </div>
                                <i class="fas fa-shopping-bag text-3xl text-orange-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow p-6 text-white mb-8">
                        <h3 class="text-xl font-bold mb-2">Seller Level: ${seller.seller_level.toUpperCase()}</h3>
                        <p>Total Sales: ${seller.total_sales} transactions</p>
                    </div>
                `;
            } catch (error) {
                console.error('Failed to load seller dashboard:', error);
            }
        }

        async function loadAdminDashboard() {
            try {
                const res = await fetch(API_BASE + '/dashboard/admin', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await res.json();
                const stats = data.data.stats;
                
                document.getElementById('dashboardContent').innerHTML = `
                    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm">Total Users</p>
                                    <p class="text-2xl font-bold">${stats.total_users}</p>
                                </div>
                                <i class="fas fa-users text-3xl text-indigo-600"></i>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm">Total Products</p>
                                    <p class="text-2xl font-bold">${stats.total_products}</p>
                                </div>
                                <i class="fas fa-box text-3xl text-purple-600"></i>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm">Total Orders</p>
                                    <p class="text-2xl font-bold">${stats.total_orders}</p>
                                </div>
                                <i class="fas fa-shopping-cart text-3xl text-orange-600"></i>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm">Platform Revenue</p>
                                    <p class="text-2xl font-bold text-green-600">Rp ${parseInt(stats.platform_fees).toLocaleString('id-ID')}</p>
                                </div>
                                <i class="fas fa-dollar-sign text-3xl text-green-600"></i>
                            </div>
                        </div>
                    </div>
                `;
            } catch (error) {
                console.error('Failed to load admin dashboard:', error);
            }
        }

        async function loadRecentOrders() {
            try {
                const res = await fetch(API_BASE + '/orders?limit=5', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await res.json();
                const orders = data.data.orders;
                
                if (orders.length === 0) {
                    document.getElementById('recentOrders').innerHTML = '<p class="text-gray-600">No orders yet</p>';
                    return;
                }
                
                document.getElementById('recentOrders').innerHTML = `
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-3">Order #</th>
                                    <th class="text-left py-3">Seller</th>
                                    <th class="text-left py-3">Amount</th>
                                    <th class="text-left py-3">Status</th>
                                    <th class="text-left py-3">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${orders.map(order => `
                                    <tr class="border-b">
                                        <td class="py-3">${order.order_number}</td>
                                        <td class="py-3">${order.seller_username}</td>
                                        <td class="py-3">Rp ${parseInt(order.total_amount).toLocaleString('id-ID')}</td>
                                        <td class="py-3">
                                            <span class="px-2 py-1 bg-${getStatusColor(order.status)} rounded text-xs">
                                                ${order.status}
                                            </span>
                                        </td>
                                        <td class="py-3">${new Date(order.created_at).toLocaleDateString()}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } catch (error) {
                console.error('Failed to load recent orders:', error);
            }
        }

        function getStatusColor(status) {
            const colors = {
                'completed': 'green-100 text-green-600',
                'processing': 'blue-100 text-blue-600',
                'delivered': 'purple-100 text-purple-600',
                'cancelled': 'red-100 text-red-600',
                'disputed': 'orange-100 text-orange-600'
            };
            return colors[status] || 'gray-100 text-gray-600';
        }

        document.getElementById('logoutBtn').addEventListener('click', () => {
            localStorage.removeItem('access_token');
            localStorage.removeItem('refresh_token');
            localStorage.removeItem('user');
            window.location.href = '/login';
        });

        loadDashboard();
    </script>
</body>
</html>

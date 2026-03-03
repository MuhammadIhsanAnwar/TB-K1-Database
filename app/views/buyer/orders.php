<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Lapak Gaming</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="text-2xl font-bold text-indigo-600">
                    <i class="fas fa-gamepad"></i> Lapak Gaming
                </a>
                <div class="flex items-center space-x-4">
                    <a href="/dashboard" class="text-gray-700 hover:text-indigo-600">Dashboard</a>
                    <a href="/wallet" class="text-gray-700 hover:text-indigo-600">Wallet</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">My Orders</h1>
        
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <div class="flex space-x-4">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg">All</button>
                    <button class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Processing</button>
                    <button class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Delivered</button>
                    <button class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Completed</button>
                </div>
            </div>
            
            <div id="ordersList" class="p-6">
                <div class="text-center py-12">
                    <i class="fas fa-spinner fa-spin text-4xl text-indigo-600"></i>
                    <p class="mt-4 text-gray-600">Loading orders...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = window.location.origin + '/api';
        const token = localStorage.getItem('access_token');
        
        if (!token) window.location.href = '/login';

        async function loadOrders() {
            try {
                const res = await fetch(API_BASE + '/orders', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await res.json();
                const orders = data.data.orders;
                
                if (orders.length === 0) {
                    document.getElementById('ordersList').innerHTML = `
                        <div class="text-center py-12">
                            <i class="fas fa-shopping-bag text-6xl text-gray-300"></i>
                            <p class="mt-4 text-gray-600">No orders yet</p>
                            <a href="/products" class="mt-4 inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg">
                                Start Shopping
                            </a>
                        </div>
                    `;
                    return;
                }
                
                document.getElementById('ordersList').innerHTML = orders.map(order => `
                    <div class="border rounded-lg p-6 mb-4 hover:shadow-lg transition">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-lg">${order.order_number}</h3>
                                <p class="text-sm text-gray-600">${new Date(order.created_at).toLocaleString()}</p>
                            </div>
                            <span class="px-3 py-1 bg-${getStatusColor(order.status)} rounded-full text-sm">
                                ${order.status.replace('_', ' ').toUpperCase()}
                            </span>
                        </div>
                        
                        <div class="border-t border-b py-4 my-4">
                            <p class="text-sm text-gray-600">Seller: <span class="font-semibold">${order.seller_username}</span></p>
                            <p class="text-xl font-bold text-indigo-600 mt-2">Rp ${parseInt(order.total_amount).toLocaleString('id-ID')}</p>
                        </div>
                        
                        <div class="flex space-x-2">
                            <a href="/order/${order.id}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                View Details
                            </a>
                            ${order.status === 'delivered' ? `
                                <button onclick="confirmOrder(${order.id})" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                    Confirm Delivery
                                </button>
                            ` : ''}
                        </div>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Failed to load orders:', error);
            }
        }

        function getStatusColor(status) {
            const colors = {
                'completed': 'green-500 text-white',
                'processing': 'blue-500 text-white',
                'delivered': 'purple-500 text-white',
                'pending_payment': 'yellow-500 text-white',
                'cancelled': 'red-500 text-white'
            };
            return colors[status] || 'gray-500 text-white';
        }

        async function confirmOrder(orderId) {
            if (!confirm('Confirm that you have received the digital items?')) return;
            
            try {
                await fetch(API_BASE + `/orders/${orderId}/confirm`, {
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                alert('Order confirmed! Payment released to seller.');
                loadOrders();
            } catch (error) {
                alert('Failed to confirm order');
            }
        }

        loadOrders();
    </script>
</body>
</html>

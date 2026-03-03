<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Products - Lapak Gaming</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <a href="/seller/orders" class="text-gray-700 hover:text-indigo-600">Sales Orders</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">My Products</h1>
            <button onclick="showAddProductModal()" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i class="fas fa-plus mr-2"></i>Add New Product
            </button>
        </div>
        
        <div id="productsList" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center py-12 col-span-full">
                <i class="fas fa-spinner fa-spin text-4xl text-indigo-600"></i>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = window.location.origin + '/api';
        const token = localStorage.getItem('access_token');
        
        if (!token) window.location.href = '/login';

        async function loadProducts() {
            try {
                const res = await fetch(API_BASE + '/products/my-products', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await res.json();
                const products = data.data.products;
                
                if (products.length === 0) {
                    document.getElementById('productsList').innerHTML = `
                        <div class="text-center py-12 col-span-full">
                            <i class="fas fa-box text-6xl text-gray-300"></i>
                            <p class="mt-4 text-gray-600">No products yet</p>
                        </div>
                    `;
                    return;
                }
                
                document.getElementById('productsList').innerHTML = products.map(product => `
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                        <img src="${product.thumbnail || '/assets/img/placeholder.jpg'}" 
                             class="w-full h-48 object-cover rounded-t-lg">
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold">${product.name}</h3>
                                <span class="px-2 py-1 ${product.is_active ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'} rounded text-xs">
                                    ${product.is_active ? 'Active' : 'Inactive'}
                                </span>
                            </div>
                            <p class="text-lg font-bold text-indigo-600">Rp ${parseInt(product.price).toLocaleString('id-ID')}</p>
                            <p class="text-sm text-gray-600 mt-2">Stock: ${product.stock_quantity}</p>
                            <p class="text-sm text-gray-600">Sold: ${product.sold_count}</p>
                            <div class="flex space-x-2 mt-4">
                                <button class="flex-1 px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
                                    Edit
                                </button>
                                <button class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Failed to load products:', error);
            }
        }

        async function showAddProductModal() {
            await Swal.fire({
                icon: 'info',
                title: 'Add Product',
                text: 'Add product modal will appear here. For now, use the API directly.',
                confirmButtonColor: '#4f46e5'
            });
        }

        loadProducts();
    </script>
</body>
</html>

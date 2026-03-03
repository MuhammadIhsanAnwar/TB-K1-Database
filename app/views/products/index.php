<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Lapak Gaming</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navbar (same as home) -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="text-2xl font-bold text-indigo-600">
                    <i class="fas fa-gamepad"></i> Lapak Gaming
                </a>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-700 hover:text-indigo-600">Home</a>
                    <a href="/products" class="text-indigo-600 font-semibold">Products</a>
                    <a href="/login" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Filters Sidebar -->
            <aside class="w-full md:w-64 space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold mb-4">Categories</h3>
                    <div id="categoriesFilter" class="space-y-2">
                        <!-- Categories will be loaded here -->
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold mb-4">Price Range</h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2" data-price="0-100000">
                            Under 100K
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2" data-price="100000-500000">
                            100K - 500K
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2" data-price="500000-1000000">
                            500K - 1M
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2" data-price="1000000-999999999">
                            Above 1M
                        </label>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold mb-4">Seller Level</h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2" value="platinum">
                            <span class="px-2 py-1 bg-purple-200 text-xs rounded">PLATINUM</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2" value="gold">
                            <span class="px-2 py-1 bg-yellow-200 text-xs rounded">GOLD</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2" value="silver">
                            <span class="px-2 py-1 bg-gray-200 text-xs rounded">SILVER</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2" value="bronze">
                            <span class="px-2 py-1 bg-orange-200 text-xs rounded">BRONZE</span>
                        </label>
                    </div>
                </div>
            </aside>

            <!-- Products Grid -->
            <main class="flex-1">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold">All Products</h1>
                        <p class="text-gray-600" id="productCount">Loading...</p>
                    </div>
                    <select id="sortBy" class="px-4 py-2 border rounded-lg">
                        <option value="latest">Latest</option>
                        <option value="popular">Most Popular</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="rating">Top Rated</option>
                    </select>
                </div>

                <div id="productsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Products will be loaded here -->
                </div>

                <div id="loadMore" class="text-center mt-8">
                    <button class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Load More
                    </button>
                </div>
            </main>
        </div>
    </div>

    <script>
        const API_BASE = window.location.origin + '/api';
        let currentPage = 1;
        let allProducts = [];

        async function loadProducts() {
            try {
                const response = await fetch(API_BASE + '/products?limit=100');
                const data = await response.json();
                
                allProducts = data.data.products;
                displayProducts(allProducts);
                document.getElementById('productCount').textContent = `${allProducts.length} products found`;
            } catch (error) {
                console.error('Failed to load products:', error);
            }
        }

        function displayProducts(products) {
            const grid = document.getElementById('productsGrid');
            
            grid.innerHTML = products.map(product => `
                <div class="bg-white rounded-lg shadow hover:shadow-xl transition overflow-hidden">
                    <div class="relative">
                        <img src="${product.thumbnail || '/assets/img/placeholder.jpg'}" 
                             alt="${product.name}" 
                             class="w-full h-48 object-cover">
                        ${product.discount_price ? `
                            <span class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-sm">
                                -${Math.round((1 - product.discount_price / product.price) * 100)}%
                            </span>
                        ` : ''}
                    </div>
                    
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs px-2 py-1 bg-indigo-100 text-indigo-600 rounded">
                                ${product.category_name}
                            </span>
                            <div class="flex items-center text-yellow-500 text-sm">
                                <i class="fas fa-star"></i>
                                <span class="ml-1">${product.rating_avg || '0.0'}</span>
                            </div>
                        </div>
                        
                        <h3 class="font-semibold mb-2 h-12 overflow-hidden">${product.name}</h3>
                        
                        <div class="mb-3">
                            ${product.discount_price ? `
                                <div class="text-gray-500 line-through text-sm">Rp ${parseInt(product.price).toLocaleString('id-ID')}</div>
                                <div class="text-lg font-bold text-indigo-600">Rp ${parseInt(product.discount_price).toLocaleString('id-ID')}</div>
                            ` : `
                                <div class="text-lg font-bold text-indigo-600">Rp ${parseInt(product.price).toLocaleString('id-ID')}</div>
                            `}
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-600 mb-3">
                            <span class="px-2 py-1 bg-${getSellerLevelColor(product.seller_level)} rounded text-xs mr-2">
                                ${product.seller_level.toUpperCase()}
                            </span>
                            <span>${product.seller_username}</span>
                        </div>
                        
                        <a href="/product/${product.id}" 
                           class="block w-full bg-indigo-600 text-white text-center py-2 rounded hover:bg-indigo-700 transition">
                            View Details
                        </a>
                    </div>
                </div>
            `).join('');
        }

        function getSellerLevelColor(level) {
            const colors = { bronze: 'orange-200', silver: 'gray-200', gold: 'yellow-200', platinum: 'purple-200' };
            return colors[level] || 'gray-200';
        }

        loadProducts();
    </script>
</body>
</html>

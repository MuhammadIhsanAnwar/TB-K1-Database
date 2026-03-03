<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail - Lapak Gaming</title>
    <script>
        tailwind.config = { darkMode: 'class' };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/assets/css/theme.css">
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
                    <a href="/" class="text-gray-700 hover:text-indigo-600">Home</a>
                    <a href="/products" class="text-gray-700 hover:text-indigo-600">Products</a>
                    <button id="darkModeToggle" class="text-gray-600 hover:text-gray-900" aria-label="Toggle dark mode">
                        <i class="fas fa-moon"></i>
                    </button>
                    <a href="/login" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div id="productDetail" class="bg-white rounded-lg shadow-lg p-8">
            <!-- Loading state -->
            <div class="text-center py-12">
                <i class="fas fa-spinner fa-spin text-4xl text-indigo-600"></i>
                <p class="mt-4 text-gray-600">Loading product details...</p>
            </div>
        </div>

        <!-- Reviews Section -->
        <div id="reviewsSection" class="mt-8 bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold mb-6">Customer Reviews</h2>
            <div id="reviewsList"></div>
        </div>
    </div>

    <script src="/assets/js/theme.js"></script>
    <script>
        const API_BASE = window.location.origin + '/api';
        const productId = window.location.pathname.split('/').pop();

        async function loadProductDetail() {
            try {
                const response = await fetch(API_BASE + `/products/${productId}`);
                const data = await response.json();
                const product = data.data.product;
                
                document.title = `${product.name} - Lapak Gaming`;
                
                document.getElementById('productDetail').innerHTML = `
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <img src="${product.thumbnail || '/assets/img/placeholder.jpg'}" 
                                 alt="${product.name}" 
                                 class="w-full rounded-lg">
                        </div>
                        
                        <div>
                            <div class="flex items-center mb-4">
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-600 rounded-full text-sm">
                                    ${product.category_name}
                                </span>
                                ${product.is_featured ? '<span class="ml-2 px-3 py-1 bg-yellow-100 text-yellow-600 rounded-full text-sm">Featured</span>' : ''}
                            </div>
                            
                            <h1 class="text-3xl font-bold mb-4">${product.name}</h1>
                            
                            <div class="flex items-center mb-4">
                                <div class="flex items-center text-yellow-500 mr-4">
                                    <i class="fas fa-star"></i>
                                    <span class="ml-1 font-semibold">${product.rating_avg || '0.0'}</span>
                                    <span class="text-gray-600 ml-1">(${product.rating_count || 0} reviews)</span>
                                </div>
                                <div class="text-gray-600">
                                    <i class="fas fa-shopping-cart mr-1"></i>
                                    ${product.sold_count} sold
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                ${product.discount_price ? `
                                    <div class="text-gray-500 line-through text-xl">Rp ${parseInt(product.price).toLocaleString('id-ID')}</div>
                                    <div class="text-3xl font-bold text-indigo-600">Rp ${parseInt(product.discount_price).toLocaleString('id-ID')}</div>
                                    <span class="inline-block mt-2 px-3 py-1 bg-red-500 text-white rounded-full text-sm">
                                        Save ${Math.round((1 - product.discount_price / product.price) * 100)}%
                                    </span>
                                ` : `
                                    <div class="text-3xl font-bold text-indigo-600">Rp ${parseInt(product.price).toLocaleString('id-ID')}</div>
                                `}
                            </div>
                            
                            <div class="border-t border-b py-4 mb-6">
                                <h3 class="font-semibold mb-2">Seller Information</h3>
                                <div class="flex items-center">
                                    <span class="px-3 py-1 bg-${getSellerLevelColor(product.seller_level)} rounded text-sm mr-2">
                                        ${product.seller_level.toUpperCase()}
                                    </span>
                                    <span class="font-semibold">${product.seller_username}</span>
                                    <span class="ml-auto text-sm text-gray-600">${product.seller_total_sales} sales</span>
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <h3 class="font-semibold mb-2">Description</h3>
                                <p class="text-gray-700 whitespace-pre-line">${product.description}</p>
                            </div>
                            
                            <div class="mb-6">
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-truck mr-2"></i>Delivery Method: ${product.delivery_method === 'auto' ? 'Instant Delivery' : 'Manual (1-24 hours)'}
                                </div>
                                <div class="text-sm text-gray-600 mt-2">
                                    <i class="fas fa-box mr-2"></i>Stock: ${product.stock_type === 'unlimited' ? 'Unlimited' : product.stock_quantity + ' remaining'}
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <button onclick="buyNow()" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                                    <i class="fas fa-shopping-cart mr-2"></i>Buy Now
                                </button>
                                <button onclick="chatSeller()" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                                    <i class="fas fa-comments mr-2"></i>Chat Seller
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                loadReviews();
            } catch (error) {
                console.error('Failed to load product:', error);
                document.getElementById('productDetail').innerHTML = `
                    <div class="text-center py-12">
                        <i class="fas fa-exclamation-circle text-4xl text-red-600"></i>
                        <p class="mt-4 text-gray-600">Failed to load product details</p>
                        <a href="/products" class="mt-4 inline-block text-indigo-600 hover:underline">Back to Products</a>
                    </div>
                `;
            }
        }

        async function loadReviews() {
            try {
                const response = await fetch(API_BASE + `/reviews/product/${productId}?limit=10`);
                const data = await response.json();
                const reviews = data.data.reviews;
                
                if (reviews.length === 0) {
                    document.getElementById('reviewsList').innerHTML = '<p class="text-gray-600">No reviews yet</p>';
                    return;
                }
                
                document.getElementById('reviewsList').innerHTML = reviews.map(review => `
                    <div class="border-b pb-4 mb-4">
                        <div class="flex items-center mb-2">
                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(review.buyer_username)}" 
                                 class="w-10 h-10 rounded-full mr-3">
                            <div>
                                <div class="font-semibold">${review.buyer_username}</div>
                                <div class="flex items-center text-yellow-500 text-sm">
                                    ${[...Array(review.rating)].map(() => '<i class="fas fa-star"></i>').join('')}
                                </div>
                            </div>
                            <span class="ml-auto text-sm text-gray-600">${new Date(review.created_at).toLocaleDateString()}</span>
                        </div>
                        <p class="text-gray-700">${review.comment}</p>
                        ${review.seller_response ? `
                            <div class="mt-2 ml-8 bg-gray-50 p-3 rounded">
                                <div class="font-semibold text-sm mb-1">Seller Response:</div>
                                <p class="text-gray-700 text-sm">${review.seller_response}</p>
                            </div>
                        ` : ''}
                    </div>
                `).join('');
            } catch (error) {
                console.error('Failed to load reviews:', error);
            }
        }

        function getSellerLevelColor(level) {
            const colors = { bronze: 'orange-200', silver: 'gray-200', gold: 'yellow-200', platinum: 'purple-200' };
            return colors[level] || 'gray-200';
        }

        async function buyNow() {
            const token = localStorage.getItem('access_token');
            if (!token) {
                await Swal.fire({
                    icon: 'warning',
                    title: 'Login Required',
                    text: 'Please login to make a purchase',
                    confirmButtonColor: '#4f46e5'
                });
                window.location.href = '/login';
                return;
            }
            // Redirect to order page
            await Swal.fire({
                icon: 'info',
                title: 'Checkout',
                text: 'This will redirect to checkout. Feature coming soon!',
                confirmButtonColor: '#4f46e5'
            });
        }

        async function chatSeller() {
            const token = localStorage.getItem('access_token');
            if (!token) {
                await Swal.fire({
                    icon: 'warning',
                    title: 'Login Required',
                    text: 'Please login to chat with seller',
                    confirmButtonColor: '#4f46e5'
                });
                window.location.href = '/login';
                return;
            }
            await Swal.fire({
                icon: 'info',
                title: 'Chat',
                text: 'Chat feature coming soon!',
                confirmButtonColor: '#4f46e5'
            });
        }

        loadProductDetail();
    </script>
</body>
</html>

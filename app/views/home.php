<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapak Gaming - Digital Marketplace</title>
    <script>
        tailwind.config = {
            darkMode: 'class'
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .skeleton { animation: skeleton-loading 1s linear infinite alternate; }
        @keyframes skeleton-loading { 0% { background-color: hsl(200, 20%, 80%); } 100% { background-color: hsl(200, 20%, 95%); } }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-200">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg sticky top-0 z-50 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-8">
                    <a href="/" class="text-2xl font-bold text-indigo-600">
                        <i class="fas fa-gamepad"></i> Lapak Gaming
                    </a>
                    <div class="hidden md:flex space-x-6">
                        <a href="/" class="text-gray-700 hover:text-indigo-600 dark:text-gray-200 dark:hover:text-indigo-400">Home</a>
                        <a href="/products" class="text-gray-700 hover:text-indigo-600 dark:text-gray-200 dark:hover:text-indigo-400">Products</a>
                        <a href="/products" class="text-gray-700 hover:text-indigo-600 dark:text-gray-200 dark:hover:text-indigo-400">Categories</a>
                    </div>
                </div>
                
                <!-- Search Bar -->
                <div class="flex-1 max-w-md mx-8 hidden lg:block">
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Search products..."
                               class="w-full px-4 py-2 pl-10 rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white text-gray-900 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                           <div id="searchResults" class="absolute w-full bg-white mt-2 rounded-lg shadow-lg hidden max-h-96 overflow-y-auto dark:bg-gray-800"></div>
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <button id="darkModeToggle" class="text-gray-600 hover:text-gray-900 dark:text-gray-200 dark:hover:text-white">
                        <i class="fas fa-moon"></i>
                    </button>
                    
                    <div class="relative" id="notificationDropdown">
                        <button id="notificationBtn" class="text-gray-600 hover:text-gray-900 relative dark:text-gray-200 dark:hover:text-white">
                            <i class="fas fa-bell text-xl"></i>
                            <span id="notifBadge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">0</span>
                        </button>
                    </div>
                    
                    <div class="relative" id="chatDropdown">
                        <button id="chatBtn" class="text-gray-600 hover:text-gray-900 relative dark:text-gray-200 dark:hover:text-white">
                            <i class="fas fa-comments text-xl"></i>
                            <span id="chatBadge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">0</span>
                        </button>
                    </div>
                    
                    <div id="userMenu">
                        <button id="loginBtn" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                            Login
                        </button>
                        <div id="userDropdown" class="hidden">
                            <button class="flex items-center space-x-2">
                                <img id="userAvatar" src="https://ui-avatars.com/api/?name=User" class="w-8 h-8 rounded-full">
                                <span id="userName">User</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-4">Digital Marketplace</h1>
            <p class="text-xl mb-8">Buy & Sell Game Accounts, Items, Vouchers & More</p>
            <div class="flex justify-center space-x-4">
                <a href="/register" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100">
                    Start Selling
                </a>
                <a href="/products" class="bg-indigo-800 px-8 py-3 rounded-lg font-semibold hover:bg-indigo-900">
                    Browse Products
                </a>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="py-12 bg-white dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8 text-center">Popular Categories</h2>
            <div id="categoriesGrid" class="grid grid-cols-2 md:grid-cols-5 gap-6">
                <!-- Categories will be loaded here -->
                <div class="skeleton rounded-lg h-32"></div>
                <div class="skeleton rounded-lg h-32"></div>
                <div class="skeleton rounded-lg h-32"></div>
                <div class="skeleton rounded-lg h-32"></div>
                <div class="skeleton rounded-lg h-32"></div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-12 dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Featured Products</h2>
                <div class="space-x-4">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Popular</button>
                    <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg dark:bg-gray-700 dark:text-gray-100">Latest</button>
                    <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg dark:bg-gray-700 dark:text-gray-100">Top Rated</button>
                </div>
            </div>
            
            <div id="productsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Products will be loaded here -->
            </div>
        </div>
    </section>

    <!-- Toast Notification -->
    <div id="toast" class="hidden fixed bottom-4 right-4 bg-gray-900 text-white px-6 py-4 rounded-lg shadow-lg z-50">
        <p id="toastMessage"></p>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Lapak Gaming</h3>
                    <p class="text-gray-400">Trusted digital marketplace for gamers</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">About Us</a></li>
                        <li><a href="#" class="hover:text-white">How It Works</a></li>
                        <li><a href="#" class="hover:text-white">Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Help Center</a></li>
                        <li><a href="#" class="hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Follow Us</h4>
                    <div class="flex space-x-4 text-2xl">
                        <a href="#" class="hover:text-indigo-400"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="hover:text-indigo-400"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="hover:text-indigo-400"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2026 Lapak Gaming. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="/assets/js/app.js"></script>
</body>
</html>

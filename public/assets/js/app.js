// Configuration
const API_BASE = window.location.origin + '/api';
const POLLING_INTERVAL = 3000; // 3 seconds

// State Management
const state = {
    user: null,
    token: localStorage.getItem('access_token'),
    refreshToken: localStorage.getItem('refresh_token')
};

// API Helper
async function apiCall(endpoint, options = {}) {
    const headers = {
        'Content-Type': 'application/json',
        ...options.headers
    };
    
    if (state.token) {
        headers['Authorization'] = `Bearer ${state.token}`;
    }
    
    try {
        const response = await fetch(API_BASE + endpoint, {
            ...options,
            headers
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            if (response.status === 401 && endpoint !== '/auth/login') {
                // Token expired, try refresh
                await refreshAccessToken();
                return apiCall(endpoint, options);
            }
            throw new Error(data.message || 'Request failed');
        }
        
        return data;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

// Authentication
async function refreshAccessToken() {
    if (!state.refreshToken) {
        logout();
        return;
    }
    
    try {
        const data = await apiCall('/auth/refresh-token', {
            method: 'POST',
            body: JSON.stringify({ refresh_token: state.refreshToken })
        });
        
        state.token = data.data.access_token;
        localStorage.setItem('access_token', state.token);
    } catch (error) {
        logout();
    }
}

function logout() {
    state.user = null;
    state.token = null;
    state.refreshToken = null;
    localStorage.removeItem('access_token');
    localStorage.removeItem('refresh_token');
    localStorage.removeItem('user');
    window.location.href = '/login';
}

// Load User Profile
async function loadUserProfile() {
    if (!state.token) return;
    
    try {
        const data = await apiCall('/user/profile');
        state.user = data.data.user;
        localStorage.setItem('user', JSON.stringify(state.user));
        updateUserUI();
    } catch (error) {
        console.error('Failed to load profile:', error);
    }
}

function updateUserUI() {
    if (state.user) {
        document.getElementById('loginBtn').classList.add('hidden');
        document.getElementById('userDropdown').classList.remove('hidden');
        document.getElementById('userName').textContent = state.user.username;
        document.getElementById('userAvatar').src = state.user.avatar || 
            `https://ui-avatars.com/api/?name=${encodeURIComponent(state.user.username)}`;
    }
}

// Load Categories
async function loadCategories() {
    try {
        const data = await apiCall('/categories');
        const grid = document.getElementById('categoriesGrid');
        
        grid.innerHTML = data.data.categories.slice(0, 5).map(cat => `
            <a href="/products/category/${cat.slug}" 
               class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition text-center">
                <i class="fas fa-${cat.icon || 'gamepad'} text-4xl text-indigo-600 mb-3"></i>
                <h3 class="font-semibold">${cat.name}</h3>
                <p class="text-sm text-gray-600">${cat.subcategories?.length || 0} items</p>
            </a>
        `).join('');
    } catch (error) {
        console.error('Failed to load categories:', error);
    }
}

// Load Products
async function loadProducts(featured = true) {
    try {
        const data = await apiCall('/products?limit=8');
        const grid = document.getElementById('productsGrid');
        
        grid.innerHTML = data.data.products.map(product => `
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
                    
                    <h3 class="font-semibold mb-2 truncate">${product.name}</h3>
                    
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            ${product.discount_price ? `
                                <div class="text-gray-500 line-through text-sm">Rp ${formatPrice(product.price)}</div>
                                <div class="text-lg font-bold text-indigo-600">Rp ${formatPrice(product.discount_price)}</div>
                            ` : `
                                <div class="text-lg font-bold text-indigo-600">Rp ${formatPrice(product.price)}</div>
                            `}
                        </div>
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-600 mb-3">
                        <span class="px-2 py-1 bg-${getSellerLevelColor(product.seller_level)} rounded text-xs mr-2">
                            ${product.seller_level.toUpperCase()}
                        </span>
                        <span>${product.seller_username}</span>
                    </div>
                    
                    <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                        <span><i class="fas fa-eye"></i> ${product.view_count}</span>
                        <span><i class="fas fa-shopping-cart"></i> ${product.sold_count} sold</span>
                    </div>
                    
                    <a href="/product/${product.id}" 
                       class="block w-full bg-indigo-600 text-white text-center py-2 rounded hover:bg-indigo-700 transition">
                        View Details
                    </a>
                </div>
            </div>
        `).join('');
    } catch (error) {
        console.error('Failed to load products:', error);
        showToast('Failed to load products', 'error');
    }
}

// Helper Functions
function formatPrice(price) {
    return parseInt(price).toLocaleString('id-ID');
}

function getSellerLevelColor(level) {
    const colors = {
        bronze: 'orange-200',
        silver: 'gray-200',
        gold: 'yellow-200',
        platinum: 'purple-200'
    };
    return colors[level] || 'gray-200';
}

// Search Functionality
let searchTimeout;
document.getElementById('searchInput')?.addEventListener('input', (e) => {
    clearTimeout(searchTimeout);
    const query = e.target.value.trim();
    
    if (query.length < 2) {
        document.getElementById('searchResults').classList.add('hidden');
        return;
    }
    
    searchTimeout = setTimeout(async () => {
        try {
            const data = await apiCall(`/products/search?q=${encodeURIComponent(query)}`);
            const resultsDiv = document.getElementById('searchResults');
            
            if (data.data.products.length === 0) {
                resultsDiv.innerHTML = '<div class="p-4 text-gray-600">No results found</div>';
            } else {
                resultsDiv.innerHTML = data.data.products.slice(0, 5).map(p => `
                    <a href="/product/${p.id}" class="flex items-center p-3 hover:bg-gray-50 border-b">
                        <img src="${p.thumbnail || '/assets/img/placeholder.jpg'}" 
                             class="w-12 h-12 object-cover rounded mr-3">
                        <div class="flex-1">
                            <div class="font-semibold truncate">${p.name}</div>
                            <div class="text-sm text-indigo-600">Rp ${formatPrice(p.discount_price || p.price)}</div>
                        </div>
                    </a>
                `).join('');
            }
            
            resultsDiv.classList.remove('hidden');
        } catch (error) {
            console.error('Search failed:', error);
        }
    }, 300);
});

// Click outside to close search
document.addEventListener('click', (e) => {
    if (!e.target.closest('#searchInput') && !e.target.closest('#searchResults')) {
        document.getElementById('searchResults')?.classList.add('hidden');
    }
});

// Toast Notification
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    
    toastMessage.textContent = message;
    toast.classList.remove('hidden');
    
    if (type === 'error') {
        toast.classList.add('bg-red-600');
        toast.classList.remove('bg-gray-900');
    } else {
        toast.classList.add('bg-gray-900');
        toast.classList.remove('bg-red-600');
    }
    
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}

// Notification Polling
async function pollNotifications() {
    if (!state.token) return;
    
    try {
        const data = await apiCall('/notifications/unread-count');
        const count = data.data.unread_count;
        const badge = document.getElementById('notifBadge');
        
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    } catch (error) {
        console.error('Failed to poll notifications:', error);
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', async () => {
    // Load saved user
    const savedUser = localStorage.getItem('user');
    if (savedUser) {
        state.user = JSON.parse(savedUser);
        updateUserUI();
    }
    
    // Load data
    await loadCategories();
    await loadProducts();
    
    if (state.token) {
        await loadUserProfile();
        
        // Start polling
        setInterval(pollNotifications, POLLING_INTERVAL);
        pollNotifications();
    }
    
    // Check dark mode preference
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark');
        // Update dark mode icon
        const darkModeIcon = document.querySelector('#darkModeToggle i');
        if (darkModeIcon) {
            darkModeIcon.classList.remove('fa-moon');
            darkModeIcon.classList.add('fa-sun');
        }
    }
    
    // Add event listeners for navbar buttons
    setupNavbarEvents();
});

// Setup Navbar Event Listeners
function setupNavbarEvents() {
    // Dark Mode Toggle
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark');
            const isDark = document.body.classList.contains('dark');
            localStorage.setItem('darkMode', isDark);
            
            // Update icon
            const icon = darkModeToggle.querySelector('i');
            if (isDark) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
        });
    }
    
    // Login button
    const loginBtn = document.getElementById('loginBtn');
    if (loginBtn) {
        loginBtn.addEventListener('click', () => {
            window.location.href = '/login';
        });
    }
    
    // Notification button
    const notificationBtn = document.getElementById('notificationBtn');
    if (notificationBtn) {
        notificationBtn.addEventListener('click', () => {
            if (!state.token) {
                alert('Please login to view notifications');
                window.location.href = '/login';
                return;
            }
            // TODO: Show notification dropdown
            alert('Notifications feature will open here');
        });
    }
    
    // Chat button
    const chatBtn = document.getElementById('chatBtn');
    if (chatBtn) {
        chatBtn.addEventListener('click', () => {
            if (!state.token) {
                alert('Please login to access chat');
                window.location.href = '/login';
                return;
            }
            // TODO: Open chat modal or redirect to chat page
            alert('Chat feature will open here');
        });
    }
    
    // User dropdown toggle
    const userDropdown = document.getElementById('userDropdown');
    if (userDropdown && state.user) {
        userDropdown.addEventListener('click', () => {
            window.location.href = '/dashboard';
        });
    }
}

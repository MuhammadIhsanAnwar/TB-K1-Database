<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lapak Gaming</title>
    <script>
        tailwind.config = { darkMode: 'class' };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/assets/css/theme.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <button id="darkModeToggle" class="fixed top-4 right-4 z-50 bg-white text-gray-700 px-3 py-2 rounded-lg shadow" aria-label="Toggle dark mode">
        <i class="fas fa-moon"></i>
    </button>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo -->
            <div class="text-center">
                <a href="/" class="text-4xl font-bold text-indigo-600">
                    <i class="fas fa-gamepad"></i> Lapak Gaming
                </a>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">Sign in to your account</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Or 
                    <a href="/register" class="font-medium text-indigo-600 hover:text-indigo-500">
                        create a new account
                    </a>
                </p>
            </div>
            
            <!-- Login Form -->
            <form id="loginForm" class="mt-8 space-y-6 bg-white p-8 rounded-lg shadow">
                <div id="errorMessage" class="hidden bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <p></p>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <input id="email" name="email" type="email" required
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-lg 
                                      focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                               placeholder="your@email.com">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1 relative">
                            <input id="password" name="password" type="password" required
                                   class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-lg 
                                          focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                   placeholder="••••••••">
                            <button type="button" id="togglePassword" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>
                    
                    <div class="text-sm">
                        <a href="/forgot-password" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Forgot your password?
                        </a>
                    </div>
                </div>
                
                <div>
                    <button type="submit" id="submitBtn"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent 
                                   text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-lock text-indigo-500 group-hover:text-indigo-400"></i>
                        </span>
                        Sign in
                    </button>
                </div>
            </form>
            
            <!-- Social Login (Optional) -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-gray-50 text-gray-500">Secure & Encrypted</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="/assets/js/theme.js"></script>
    <script>
        const API_BASE = window.location.origin + '/api';
        
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Login form submission
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const errorDiv = document.getElementById('errorMessage');
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Disable button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Signing in...';
            errorDiv.classList.add('hidden');
            
            try {
                const response = await fetch(API_BASE + '/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });
                
                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.message || 'Login failed');
                }
                
                // Store tokens
                localStorage.setItem('access_token', data.data.access_token);
                localStorage.setItem('refresh_token', data.data.refresh_token);
                localStorage.setItem('user', JSON.stringify(data.data.user));
                
                // Redirect based on role
                const user = data.data.user;
                if (user.role === 'admin') {
                    window.location.href = '/dashboard?tab=admin';
                } else if (user.role === 'seller') {
                    window.location.href = '/dashboard?tab=seller';
                } else {
                    window.location.href = '/dashboard';
                }
                
            } catch (error) {
                errorDiv.querySelector('p').textContent = error.message;
                errorDiv.classList.remove('hidden');
                
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span class="absolute left-0 inset-y-0 flex items-center pl-3"><i class="fas fa-lock text-indigo-500"></i></span>Sign in';
            }
        });
    </script>
</body>
</html>

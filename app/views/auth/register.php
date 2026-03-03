<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Lapak Gaming</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-indigo-500 to-purple-600 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <a href="/" class="text-3xl font-bold text-indigo-600">
                <i class="fas fa-gamepad"></i> Lapak Gaming
            </a>
            <h2 class="text-2xl font-bold mt-4">Create Account</h2>
            <p class="text-gray-600">Join our marketplace today</p>
        </div>

        <form id="registerForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text" name="full_name" required
                       class="w-full px-4 py-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="John Doe">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input type="text" name="username" required
                       class="w-full px-4 py-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="johndoe123">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="john@example.com">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="tel" name="phone" required
                       class="w-full px-4 py-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="081234567890">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500"
                           placeholder="••••••••">
                    <button type="button" onclick="togglePassword('password')" 
                            class="absolute right-3 top-3 text-gray-500">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Register as</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="buyer" checked class="hidden peer">
                        <div class="border-2 border-gray-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 rounded-lg p-4 text-center">
                            <i class="fas fa-shopping-cart text-2xl mb-2 text-indigo-600"></i>
                            <div class="font-semibold">Buyer</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="role" value="seller" class="hidden peer">
                        <div class="border-2 border-gray-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 rounded-lg p-4 text-center">
                            <i class="fas fa-store text-2xl mb-2 text-indigo-600"></i>
                            <div class="font-semibold">Seller</div>
                        </div>
                    </label>
                </div>
            </div>

            <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"></div>

            <button type="submit" id="submitBtn"
                    class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                Create Account
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-600">
                Already have an account? 
                <a href="/login" class="text-indigo-600 font-semibold hover:underline">Login</a>
            </p>
        </div>
    </div>

    <script>
        const API_BASE = window.location.origin + '/api';

        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);
            const submitBtn = document.getElementById('submitBtn');
            const errorDiv = document.getElementById('errorMessage');
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Creating account...';
            errorDiv.classList.add('hidden');
            
            try {
                const response = await fetch(API_BASE + '/auth/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (!response.ok) {
                    throw new Error(result.message || 'Registration failed');
                }
                
                // Show success and redirect
                await Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful',
                    text: 'Please check your email to verify your account.',
                    confirmButtonColor: '#4f46e5'
                });
                window.location.href = '/login';
                
            } catch (error) {
                errorDiv.textContent = error.message;
                errorDiv.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Create Account';
            }
        });
    </script>
</body>
</html>

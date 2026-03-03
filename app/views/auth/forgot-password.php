<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Lapak Gaming</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-indigo-500 to-purple-600 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <a href="/" class="text-3xl font-bold text-indigo-600">
                <i class="fas fa-gamepad"></i> Lapak Gaming
            </a>
            <h2 class="text-2xl font-bold mt-4">Forgot Password</h2>
            <p class="text-gray-600">Enter your email to reset password</p>
        </div>

        <form id="forgotForm" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2"></i>Email Address
                </label>
                <input type="email" name="email" required
                       class="w-full px-4 py-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="your@email.com">
            </div>

            <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"></div>
            <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"></div>

            <button type="submit" id="submitBtn"
                    class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                Send Reset Link
            </button>
        </form>

        <div class="mt-6 text-center space-y-2">
            <a href="/login" class="block text-indigo-600 font-semibold hover:underline">
                <i class="fas fa-arrow-left mr-2"></i>Back to Login
            </a>
            <p class="text-gray-600">
                Don't have an account? 
                <a href="/register" class="text-indigo-600 font-semibold hover:underline">Register</a>
            </p>
        </div>
    </div>

    <script>
        const API_BASE = window.location.origin + '/api';

        document.getElementById('forgotForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);
            const submitBtn = document.getElementById('submitBtn');
            const successDiv = document.getElementById('successMessage');
            const errorDiv = document.getElementById('errorMessage');
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';
            successDiv.classList.add('hidden');
            errorDiv.classList.add('hidden');
            
            try {
                const response = await fetch(API_BASE + '/auth/forgot-password', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (!response.ok) {
                    throw new Error(result.message || 'Request failed');
                }
                
                successDiv.textContent = 'Reset link sent! Please check your email.';
                successDiv.classList.remove('hidden');
                e.target.reset();
                
            } catch (error) {
                errorDiv.textContent = error.message;
                errorDiv.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Send Reset Link';
            }
        });
    </script>
</body>
</html>

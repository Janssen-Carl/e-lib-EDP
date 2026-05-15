<?php
/**
 * Forgot Password Page
 */
session_start();
$pageTitle = 'Forgot Password';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - E-Library System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 min-h-screen flex items-center justify-center py-12 px-4">
    
    <div class="w-full max-w-md">
        <!-- Brand Section -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-md">
                    <i class="fas fa-key text-blue-600 text-3xl"></i>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Reset Password</h1>
            <p class="text-gray-600 text-sm mt-2">Enter your email to receive reset instructions</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            
            <!-- Alert Banner -->
            <div id="alertBanner" class="hidden mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2z" clip-rule="evenodd"/>
                </svg>
                <span id="alertText" class="text-blue-700 text-sm font-medium">Check your email for reset instructions.</span>
            </div>

            <!-- Form -->
            <form id="forgotPasswordForm" class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="name@example.com"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition"
                        required
                    />
                </div>

                <button
                    type="submit"
                    class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition flex items-center justify-center gap-2"
                >
                    <i class="fas fa-envelope"></i>
                    Send Reset Link
                </button>
            </form>

            <!-- Back to Sign In Link -->
            <div class="mt-6 text-center text-sm text-gray-600">
                Remember your password?
                <a href="/pages/login.php" class="text-blue-600 hover:text-blue-700 font-medium">
                    Sign in
                </a>
            </div>
        </div>
    </div>

    <script src="/js/modules/forgot-password.js"></script>
</body>
</html>

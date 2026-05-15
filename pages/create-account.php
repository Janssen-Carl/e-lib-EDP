<?php
/**
 * Create Account / Registration Page
 */
session_start();
$pageTitle = 'Create Account';
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
                    <i class="fas fa-user-plus text-blue-600 text-3xl"></i>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Create Account</h1>
            <p class="text-gray-600 text-sm mt-2">Join our library community</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            
            <!-- Alert Banner -->
            <div id="alertBanner" class="hidden mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span id="alertText" class="text-red-700 text-sm font-medium">An error occurred.</span>
            </div>

            <!-- Form -->
            <form id="registrationForm" class="space-y-5">
                <div>
                    <label for="fullName" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input
                        type="text"
                        id="fullName"
                        name="fullName"
                        placeholder="John Doe"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition"
                        required
                    />
                </div>

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

                <div>
                    <label for="phoneNumber" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input
                        type="tel"
                        id="phoneNumber"
                        name="phoneNumber"
                        placeholder="+1 (555) 000-0000"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition"
                    />
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <input
                        type="text"
                        id="address"
                        name="address"
                        placeholder="123 Main Street"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition"
                    />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                        <input
                            type="text"
                            id="city"
                            name="city"
                            placeholder="New York"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition"
                        />
                    </div>
                    <div>
                        <label for="postalCode" class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                        <input
                            type="text"
                            id="postalCode"
                            name="postalCode"
                            placeholder="10001"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition"
                        />
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition"
                        required
                    />
                    <p class="text-xs text-gray-500 mt-2">Password must be at least 8 characters</p>
                </div>

                <div>
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <input
                        type="password"
                        id="confirmPassword"
                        name="confirmPassword"
                        placeholder="••••••••"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition"
                        required
                    />
                </div>

                <label class="flex items-start gap-2 cursor-pointer">
                    <input type="checkbox" id="agreeTerms" name="agreeTerms" class="w-4 h-4 rounded border-gray-300 mt-1" required>
                    <span class="text-sm text-gray-600">I agree to the <a href="#" class="text-blue-600 hover:underline">Terms of Service</a> and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a></span>
                </label>

                <button
                    type="submit"
                    class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition flex items-center justify-center gap-2"
                >
                    <i class="fas fa-user-check"></i>
                    Create Account
                </button>
            </form>

            <!-- Sign In Link -->
            <div class="mt-6 text-center text-sm text-gray-600">
                Already have an account?
                <a href="/pages/login.php" class="text-blue-600 hover:text-blue-700 font-medium">
                    Sign in
                </a>
            </div>
        </div>
    </div>

    <script src="/js/modules/register.js"></script>
</body>
</html>

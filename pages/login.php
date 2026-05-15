<?php
/**
 * Login Page
 */
session_start();
$pageTitle = 'Sign In';
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
<body class="bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    
    <div class="w-full max-w-md">
        <!-- Brand Section -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-md">
                    <!-- Book Icon SVG -->
                    <svg class="w-8 h-8 text-blue-600" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 8C4 8 8 6 16 6C24 6 28 8 28 8V24C28 24 24 22 16 22C8 22 4 24 4 24V8Z" fill="#2563EB" fill-opacity="0.15" stroke="#2563EB" stroke-width="1.5" stroke-linejoin="round"/>
                        <path d="M16 6V22" stroke="#2563EB" stroke-width="1.5"/>
                        <circle cx="23" cy="9" r="5" fill="#2563EB"/>
                        <path d="M21 9L22.5 10.5L25.5 7.5" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">E-Library System</h1>
            <p class="text-gray-600 text-sm mt-2">Access tons of books and other resources</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            
            <!-- Alert Banner -->
            <div id="alertBanner" class="hidden mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span id="alertText" class="text-red-700 text-sm font-medium">Invalid email or password.</span>
            </div>

            <!-- Role Toggle -->
            <div class="flex gap-0 mb-8 bg-gray-100 p-1 rounded-lg" role="tablist" aria-label="Login as">
                <button
                    class="flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-md bg-white text-blue-600 font-medium shadow-sm transition active-tab"
                    id="btnMember"
                    role="tab"
                    aria-selected="true"
                    onclick="setRole('Member')"
                >
                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                        <circle cx="10" cy="6" r="3"/>
                        <path d="M4 18c0-3.3 2.7-6 6-6s6 2.7 6 6" stroke-linecap="round"/>
                    </svg>
                    Member
                </button>
                <button
                    class="flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-md text-gray-600 font-medium transition"
                    id="btnLibrarian"
                    role="tab"
                    aria-selected="false"
                    onclick="setRole('Librarian')"
                >
                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6">
                        <rect x="4" y="3" width="12" height="14" rx="2"/>
                        <circle cx="10" cy="8" r="2.2"/>
                        <path d="M6 15c0-2.2 1.8-4 4-4s4 1.8 4 4" stroke-linecap="round"/>
                    </svg>
                    Librarian
                </button>
            </div>

            <!-- Form -->
            <form id="loginForm" class="space-y-5">
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
                    <div class="error-msg hidden mt-2 text-red-600 text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>Please enter a valid email</span>
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
                    <div class="error-msg hidden mt-2 text-red-600 text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>Password must be at least 6 characters</span>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-gray-300">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="/pages/forgot-password.php" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Forgot password?
                    </a>
                </div>

                <button
                    type="submit"
                    class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition flex items-center justify-center gap-2"
                >
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </button>
            </form>

            <!-- Sign Up Link -->
            <div class="mt-6 text-center text-sm text-gray-600">
                Don't have an account?
                <a href="/pages/create-account.php" class="text-blue-600 hover:text-blue-700 font-medium">
                    Create one now
                </a>
            </div>
        </div>
    </div>

    <script src="/js/modules/login.js"></script>
</body>
</html>

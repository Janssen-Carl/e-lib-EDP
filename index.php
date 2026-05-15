<?php
/**
 * Landing Page / Home Page
 */
$pageTitle = 'Welcome to E-Library';
session_start();
$isAuthenticated = isset($_SESSION['user']);
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
<body class="bg-gradient-to-br from-blue-50 via-blue-50 to-blue-100 text-gray-900">
    
    <!-- Header Navigation -->
    <header class="sticky top-0 z-100 bg-white border-b border-gray-200 shadow-sm">
        <nav class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center gap-8">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-3 flex-shrink-0 no-underline">
                <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                    <i class="fas fa-book text-blue-600 text-xl"></i>
                </div>
                <div>
                    <div class="font-bold text-gray-900">E-Library</div>
                    <div class="text-xs text-gray-600">Library System</div>
                </div>
            </a>
            
            <!-- Nav Right -->
            <div class="flex items-center gap-4 ml-auto">
                <a href="/pages/login.php" class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg text-sm font-medium transition">
                    Sign In
                </a>
                <a href="/pages/create-account.php" class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg text-sm font-medium transition">
                    Create Account
                </a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center py-12 px-4">
        <div class="max-w-7xl mx-auto w-full">
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-gray-900 mb-4">Welcome to E-Library System</h1>
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    Your comprehensive solution for digital library management. Manage books, track borrowing, and generate insights.
                </p>
                
                <!-- Search Box -->
                <div class="max-w-md mx-auto mb-8">
                    <input 
                        type="text" 
                        placeholder="Search books, literatures..." 
                        class="w-full px-6 py-3 rounded-lg border-2 border-gray-300 focus:border-blue-600 focus:outline-none transition"
                    >
                </div>
                
                <!-- CTA Buttons -->
                <div class="flex justify-center gap-4 flex-wrap">
                    <a href="/pages/login.php" class="px-8 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                        Get Started
                    </a>
                    <a href="#features" class="px-8 py-3 bg-white text-blue-600 border-2 border-blue-600 rounded-lg font-medium hover:bg-blue-50 transition">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 px-4 bg-white" id="features">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-4xl font-bold text-center mb-12">Core Features</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature Card 1 -->
                <div class="p-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-blue-600 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-book text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Book Management</h3>
                    <p class="text-gray-600">
                        Comprehensive catalog management with advanced search, filtering, and categorization capabilities.
                    </p>
                </div>

                <!-- Feature Card 2 -->
                <div class="p-8 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-200 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-green-600 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-clipboard-list text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Borrowing & Returns</h3>
                    <p class="text-gray-600">
                        Streamlined borrowing process with automatic due date tracking and return management.
                    </p>
                </div>

                <!-- Feature Card 3 -->
                <div class="p-8 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-200 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-purple-600 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-bell text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Smart Notifications</h3>
                    <p class="text-gray-600">
                        Automated alerts for overdue books, new arrivals, and important system notifications.
                    </p>
                </div>

                <!-- Feature Card 4 -->
                <div class="p-8 bg-gradient-to-br from-orange-50 to-red-50 rounded-xl border border-orange-200 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-orange-600 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Analytics & Reports</h3>
                    <p class="text-gray-600">
                        Detailed reports on library activity, borrowing trends, and inventory status with export options.
                    </p>
                </div>

                <!-- Feature Card 5 -->
                <div class="p-8 bg-gradient-to-br from-cyan-50 to-blue-50 rounded-xl border border-cyan-200 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-cyan-600 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-user text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">User Management</h3>
                    <p class="text-gray-600">
                        Secure user administration with role-based access control and activity tracking.
                    </p>
                </div>

                <!-- Feature Card 6 -->
                <div class="p-8 bg-gradient-to-br from-red-50 to-pink-50 rounded-xl border border-red-200 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-red-600 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-lock text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Security</h3>
                    <p class="text-gray-600">
                        Enterprise-grade security with encrypted data storage and secure authentication mechanisms.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h4 class="text-white font-bold mb-4">E-Library System</h4>
                    <p class="text-sm">Comprehensive library management solution for the digital age.</p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Features</h4>
                    <ul class="text-sm space-y-2">
                        <li><a href="#" class="hover:text-white transition">Book Management</a></li>
                        <li><a href="#" class="hover:text-white transition">Borrowing</a></li>
                        <li><a href="#" class="hover:text-white transition">Reports</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Support</h4>
                    <ul class="text-sm space-y-2">
                        <li><a href="#" class="hover:text-white transition">Documentation</a></li>
                        <li><a href="#" class="hover:text-white transition">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Legal</h4>
                    <ul class="text-sm space-y-2">
                        <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition">License</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-sm">
                <p>&copy; <?php echo date('Y'); ?> E-Library System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="/js/main.js"></script>
</body>
</html>

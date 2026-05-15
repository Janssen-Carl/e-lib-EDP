<?php
/**
 * Change Password Page
 */
session_start();
$pageTitle = 'Change Password';
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
<body class="bg-gray-50">
    
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center gap-8">
            <a href="/" class="flex items-center gap-3 flex-shrink-0">
                <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg">
                    <i class="fas fa-book text-blue-600"></i>
                </div>
                <div>
                    <div class="font-bold text-gray-900 text-sm">E-Library</div>
                </div>
            </a>
            <div class="ml-auto">
                <a href="/pages/logout.php" class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg text-sm font-medium transition">
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-2xl mx-auto px-4 py-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                <i class="fas fa-lock text-blue-600"></i> Change Password
            </h1>
            <p class="text-gray-600">Update your account password</p>
        </div>

        <!-- Alert Container -->
        <div id="alertContainer" class="mb-6"></div>

        <!-- Main Card -->
        <div class="bg-white rounded-lg shadow-sm p-8">
            
            <form id="changePasswordForm" class="space-y-6 max-w-md">
                <div>
                    <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input 
                        type="password" 
                        id="currentPassword" 
                        name="currentPassword" 
                        placeholder="••••••••" 
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition" 
                        required
                    >
                </div>

                <div>
                    <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input 
                        type="password" 
                        id="newPassword" 
                        name="newPassword" 
                        placeholder="••••••••" 
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition" 
                        required
                    >
                    <p class="text-xs text-gray-500 mt-2">Password must be at least 8 characters</p>
                </div>

                <div>
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input 
                        type="password" 
                        id="confirmPassword" 
                        name="confirmPassword" 
                        placeholder="••••••••" 
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition" 
                        required
                    >
                </div>

                <div class="flex gap-4 pt-6">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        <i class="fas fa-save"></i> Update Password
                    </button>
                    <a href="javascript:history.back()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="/js/modules/change-password.js"></script>
</body>
</html>

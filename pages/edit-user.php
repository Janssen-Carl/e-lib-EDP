<?php
/**
 * Edit User Page
 */
session_start();
$pageTitle = 'Edit User';
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
                <i class="fas fa-user-edit text-blue-600"></i> Edit User
            </h1>
            <p class="text-gray-600">Update user information</p>
        </div>

        <!-- Alert Container -->
        <div id="alertContainer" class="mb-6"></div>

        <!-- Main Card -->
        <div class="bg-white rounded-lg shadow-sm p-8">
            
            <form id="editUserForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input type="text" id="firstName" name="firstName" placeholder="John" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition" required>
                    </div>
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                        <input type="text" id="lastName" name="lastName" placeholder="Doe" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition" required>
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="john@example.com" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition" required>
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select id="role" name="role" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition" required>
                        <option value="Member">Member</option>
                        <option value="Librarian">Librarian</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>

                <div>
                    <label for="phoneNumber" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="+1 (555) 000-0000" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <input type="text" id="address" name="address" placeholder="123 Main Street" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                        <input type="text" id="city" name="city" placeholder="New York" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State/Province</label>
                        <input type="text" id="state" name="state" placeholder="NY" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                    </div>
                    <div>
                        <label for="postalCode" class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                        <input type="text" id="postalCode" name="postalCode" placeholder="10001" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" id="resetPassword" name="resetPassword" class="w-4 h-4 rounded border-gray-300">
                        <span class="text-sm text-gray-700">Reset password on next login</span>
                    </label>
                </div>

                <div class="flex gap-4 pt-6">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="/pages/user-management.php" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="/js/modules/edit-user.js"></script>
</body>
</html>

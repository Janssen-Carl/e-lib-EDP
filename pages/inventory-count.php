<?php
/**
 * Inventory Count Page
 */
session_start();
$pageTitle = 'Inventory Count';
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
    <div class="max-w-7xl mx-auto px-4 py-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                <i class="fas fa-boxes text-blue-600"></i> Inventory Count
            </h1>
            <p class="text-gray-600">Manage and track book inventory</p>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg p-6 border-l-4 border-blue-600 shadow-sm">
                <div class="text-3xl font-bold text-gray-900" id="statTotal">0</div>
                <div class="text-sm text-gray-600 mt-2">Total Books</div>
            </div>
            <div class="bg-white rounded-lg p-6 border-l-4 border-green-600 shadow-sm">
                <div class="text-3xl font-bold text-gray-900" id="statAvailable">0</div>
                <div class="text-sm text-gray-600 mt-2">Available</div>
            </div>
            <div class="bg-white rounded-lg p-6 border-l-4 border-orange-600 shadow-sm">
                <div class="text-3xl font-bold text-gray-900" id="statBorrowed">0</div>
                <div class="text-sm text-gray-600 mt-2">Borrowed</div>
            </div>
            <div class="bg-white rounded-lg p-6 border-l-4 border-red-600 shadow-sm">
                <div class="text-3xl font-bold text-gray-900" id="statDamaged">0</div>
                <div class="text-sm text-gray-600 mt-2">Damaged</div>
            </div>
        </div>

        <!-- Alert Container -->
        <div id="alertContainer" class="mb-6"></div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-3 mb-6">
            <button onclick="openAddBookModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                <i class="fas fa-plus"></i> Add Book
            </button>
            <button onclick="loadInventoryData()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                <i class="fas fa-redo"></i> Refresh
            </button>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Title</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ISBN</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Author</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Total</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Available</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Borrowed</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p>Loading inventory...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Book Modal -->
    <div id="addBookModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Add New Book</h2>
                <button onclick="closeAddBookModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                    &times;
                </button>
            </div>
            
            <div id="bookFormAlert" class="mb-4"></div>

            <form id="addBookForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" id="bookTitle" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Author *</label>
                    <input type="text" id="bookAuthor" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ISBN</label>
                    <input type="text" id="bookISBN" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                    <input type="number" id="bookQuantity" required min="1" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                </div>

                <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    Add Book
                </button>
            </form>
        </div>
    </div>

    <script src="/js/modules/inventory-count.js"></script>
</body>
</html>

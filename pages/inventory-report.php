<?php
/**
 * Inventory Report Page
 */
session_start();
$pageTitle = 'Inventory Report';
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
                <i class="fas fa-chart-pie text-blue-600"></i> Inventory Report
            </h1>
            <p class="text-gray-600">Comprehensive inventory analysis and status</p>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg p-6 border-l-4 border-blue-600 shadow-sm">
                <div class="text-3xl font-bold text-gray-900" id="statTotal">0</div>
                <div class="text-sm text-gray-600 mt-2">Total Books</div>
            </div>
            <div class="bg-white rounded-lg p-6 border-l-4 border-green-600 shadow-sm">
                <div class="text-3xl font-bold text-gray-900" id="statAvailable">0</div>
                <div class="text-sm text-gray-600 mt-2">In Stock</div>
            </div>
            <div class="bg-white rounded-lg p-6 border-l-4 border-orange-600 shadow-sm">
                <div class="text-3xl font-bold text-gray-900" id="statOnLoan">0</div>
                <div class="text-sm text-gray-600 mt-2">On Loan</div>
            </div>
            <div class="bg-white rounded-lg p-6 border-l-4 border-red-600 shadow-sm">
                <div class="text-3xl font-bold text-gray-900" id="statUnavailable">0</div>
                <div class="text-sm text-gray-600 mt-2">Unavailable</div>
            </div>
        </div>

        <!-- Report Content -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Book Title</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ISBN</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Author</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Total</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Available</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">On Loan</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody id="reportTable">
                        <tr class="border-b border-gray-200">
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p>Loading inventory report...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="/js/modules/reports.js"></script>
</body>
</html>

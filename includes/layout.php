<?php
/**
 * Main Layout Template
 * Included by all pages to provide consistent header, navigation, and footer
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is authenticated (optional - adjust based on your auth needs)
$isAuthenticated = isset($_SESSION['user']);
$user = $_SESSION['user'] ?? null;
$pageTitle = $pageTitle ?? 'E-Library System';
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
    
    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563EB',
                        'primary-dark': '#1D4ED8',
                        'primary-light': '#EFF6FF',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        card: '0 4px 24px rgba(0,0,0,0.08), 0 1px 4px rgba(0,0,0,0.04)',
                    }
                }
            }
        }
    </script>
    
    <?php if (isset($additionalStyles)): ?>
        <?php echo $additionalStyles; ?>
    <?php endif; ?>
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
            
            <!-- Nav Center (can be populated per page) -->
            <div class="flex-1 flex justify-center">
                <?php if (isset($navCenter)): ?>
                    <?php echo $navCenter; ?>
                <?php endif; ?>
            </div>
            
            <!-- Nav Right -->
            <div class="flex items-center gap-4">
                <?php if ($isAuthenticated && $user): ?>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-700">
                            <?php echo htmlspecialchars($user['name'] ?? $user['email'] ?? 'User'); ?>
                        </span>
                        <a href="/pages/edit-profile.php" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-user-circle"></i>
                        </a>
                        <a href="/pages/change-password.php" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-lock"></i>
                        </a>
                        <a href="/pages/logout.php" class="text-red-600 hover:text-red-700 text-sm font-medium">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                <?php else: ?>
                    <a href="/pages/login.php" class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg text-sm font-medium">
                        Sign In
                    </a>
                    <a href="/pages/create-account.php" class="px-4 py-2 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-lg text-sm font-medium">
                        Create Account
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
</body>
</html>

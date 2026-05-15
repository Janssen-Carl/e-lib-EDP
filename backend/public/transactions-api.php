<?php

declare(strict_types=1);

namespace Backend;

use Exception;

/**
 * Transactions API
 * Handles all API endpoints for borrowing, return, and inventory transactions
 */

// Include autoloader and required classes
require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Http.php';
require_once __DIR__ . '/AuthService.php';
require_once __DIR__ . '/BorrowingService.php';
require_once __DIR__ . '/InventoryService.php';
require_once __DIR__ . '/ReportService.php';

// Check for phpspreadsheet - load if available
$spreadsheetPath = __DIR__ . '/../../vendor/autoload.php';
if (file_exists($spreadsheetPath)) {
    require_once $spreadsheetPath;
}

// Set CORS headers for all responses
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Expose-Headers: Content-Type, X-Total-Count');
header('Access-Control-Max-Age: 86400');

// Handle preflight requests
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

try {
    $http = new Http();
    $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $parts = explode('/', trim($path, '/'));

    // Determine endpoint
    $endpoint = $parts[count($parts) - 1] ?? '';

    // ========== BORROWING TRANSACTIONS ==========
    if ($endpoint === 'borrow' && $method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $borrowingService = new BorrowingService();
        $result = $borrowingService->createBorrowingTransaction(
            (int) $data['bookId'],
            (int) $data['userId'],
            (int) ($data['dueDays'] ?? 14),
            $data['notes'] ?? null,
            (int) ($data['createdBy'] ?? 0)
        );

        $http->response($result);
    }

    // Get borrowing transactions
    elseif ($endpoint === 'borrowing-transactions' && $method === 'GET') {
        $borrowingService = new BorrowingService();
        $result = $borrowingService->getBorrowingTransactions(
            isset($_GET['userId']) ? (int) $_GET['userId'] : null,
            $_GET['status'] ?? null,
            isset($_GET['limit']) ? (int) $_GET['limit'] : null,
            isset($_GET['offset']) ? (int) $_GET['offset'] : null
        );

        $http->response($result);
    }

    // ========== RETURN TRANSACTIONS ==========
    elseif ($endpoint === 'return-book' && $method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $borrowingService = new BorrowingService();
        $result = $borrowingService->returnBook(
            (int) $data['transactionId'],
            (int) $data['processedBy'],
            $data['bookCondition'] ?? 'Good',
            $data['notes'] ?? null
        );

        $http->response($result);
    }

    // Get return transactions
    elseif ($endpoint === 'return-transactions' && $method === 'GET') {
        $borrowingService = new BorrowingService();
        
        $startDate = null;
        $endDate = null;

        if (isset($_GET['startDate'])) {
            $startDate = \DateTime::createFromFormat('Y-m-d', $_GET['startDate']);
        }
        if (isset($_GET['endDate'])) {
            $endDate = \DateTime::createFromFormat('Y-m-d', $_GET['endDate']);
        }

        $result = $borrowingService->getReturnTransactions(
            isset($_GET['userId']) ? (int) $_GET['userId'] : null,
            $startDate,
            $endDate,
            isset($_GET['limit']) ? (int) $_GET['limit'] : null,
            isset($_GET['offset']) ? (int) $_GET['offset'] : null
        );

        $http->response($result);
    }

    // Get overdue books
    elseif ($endpoint === 'overdue-books' && $method === 'GET') {
        $borrowingService = new BorrowingService();
        $result = $borrowingService->getOverdueBooks();
        $http->response($result);
    }

    // ========== INVENTORY TRANSACTIONS ==========
    elseif ($endpoint === 'start-inventory-count' && $method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $inventoryService = new InventoryService();
        $result = $inventoryService->startInventoryCount(
            (int) $data['conductedBy'],
            $data['notes'] ?? null
        );

        $http->response($result);
    }

    // Record physical quantity
    elseif ($endpoint === 'record-quantity' && $method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $inventoryService = new InventoryService();
        $result = $inventoryService->recordPhysicalQuantity(
            (int) $data['countId'],
            (int) $data['bookId'],
            (int) $data['physicalQuantity'],
            $data['notes'] ?? null
        );

        $http->response($result);
    }

    // Complete inventory count
    elseif ($endpoint === 'complete-inventory-count' && $method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $inventoryService = new InventoryService();
        $result = $inventoryService->completeInventoryCount(
            (int) $data['countId'],
            (int) $data['conductedBy']
        );

        $http->response($result);
    }

    // Verify inventory count
    elseif ($endpoint === 'verify-inventory-count' && $method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $inventoryService = new InventoryService();
        $result = $inventoryService->verifyInventoryCount(
            (int) $data['countId'],
            (int) $data['verifiedBy']
        );

        $http->response($result);
    }

    // Get inventory counts
    elseif ($endpoint === 'inventory-counts' && $method === 'GET') {
        $inventoryService = new InventoryService();
        $result = $inventoryService->getAllInventoryCounts(
            $_GET['status'] ?? null,
            isset($_GET['limit']) ? (int) $_GET['limit'] : null,
            isset($_GET['offset']) ? (int) $_GET['offset'] : null
        );

        $http->response($result);
    }

    // Get inventory count by ID
    elseif ($endpoint === 'inventory-count' && $method === 'GET') {
        $countId = (int) ($_GET['countId'] ?? 0);

        $inventoryService = new InventoryService();
        $result = $inventoryService->getInventoryCount($countId);

        $http->response($result);
    }

    // Get inventory discrepancies
    elseif ($endpoint === 'inventory-discrepancies' && $method === 'GET') {
        $inventoryService = new InventoryService();
        $result = $inventoryService->getInventoryDiscrepancies(
            isset($_GET['countId']) ? (int) $_GET['countId'] : null
        );

        $http->response($result);
    }

    // ========== BOOK INVENTORY ==========
    elseif ($endpoint === 'book-inventory' && $method === 'GET') {
        $bookId = (int) ($_GET['bookId'] ?? 0);

        $borrowingService = new BorrowingService();
        if ($bookId > 0) {
            $result = $borrowingService->getBookInventory($bookId);
        } else {
            $result = $borrowingService->getAllInventory(
                isset($_GET['limit']) ? (int) $_GET['limit'] : null,
                isset($_GET['offset']) ? (int) $_GET['offset'] : null
            );
        }

        $http->response($result);
    }

    // ========== REPORTS ==========
    elseif ($endpoint === 'borrowing-report' && $method === 'GET') {
        $reportService = new ReportService();
        
        $startDate = null;
        $endDate = null;

        if (isset($_GET['startDate'])) {
            $startDate = \DateTime::createFromFormat('Y-m-d', $_GET['startDate']);
        }
        if (isset($_GET['endDate'])) {
            $endDate = \DateTime::createFromFormat('Y-m-d', $_GET['endDate']);
        }

        $result = $reportService->getBorrowingReport(
            $startDate,
            $endDate,
            $_GET['status'] ?? null
        );

        $http->response($result);
    }

    // Get return report
    elseif ($endpoint === 'return-report' && $method === 'GET') {
        $reportService = new ReportService();
        
        $startDate = null;
        $endDate = null;

        if (isset($_GET['startDate'])) {
            $startDate = \DateTime::createFromFormat('Y-m-d', $_GET['startDate']);
        }
        if (isset($_GET['endDate'])) {
            $endDate = \DateTime::createFromFormat('Y-m-d', $_GET['endDate']);
        }

        $result = $reportService->getReturnReport($startDate, $endDate);

        $http->response($result);
    }

    // Get inventory report
    elseif ($endpoint === 'inventory-report' && $method === 'GET') {
        $reportService = new ReportService();
        $result = $reportService->getInventoryReport(
            isset($_GET['countId']) ? (int) $_GET['countId'] : null
        );

        $http->response($result);
    }

    // Get transaction statistics
    elseif ($endpoint === 'transaction-statistics' && $method === 'GET') {
        $reportService = new ReportService();
        $result = $reportService->getTransactionStatistics();
        $http->response($result);
    }

    // Get top borrowed books
    elseif ($endpoint === 'top-borrowed-books' && $method === 'GET') {
        $reportService = new ReportService();
        $result = $reportService->getTopBorrowedBooks(
            (int) ($_GET['limit'] ?? 10)
        );
        $http->response($result);
    }

    // Get top borrowers
    elseif ($endpoint === 'top-borrowers' && $method === 'GET') {
        $reportService = new ReportService();
        $result = $reportService->getTopBorrowers(
            (int) ($_GET['limit'] ?? 10)
        );
        $http->response($result);
    }

    // ========== EXCEL EXPORT ==========
    elseif ($endpoint === 'export-borrowing-excel' && $method === 'POST') {
        if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            $http->response([
                'success' => false,
                'message' => 'PhpSpreadsheet library not installed. Run: composer require phpoffice/phpspreadsheet'
            ]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $reportService = new ReportService();
        $report = $reportService->getBorrowingReport(
            isset($data['startDate']) ? \DateTime::createFromFormat('Y-m-d', $data['startDate']) : null,
            isset($data['endDate']) ? \DateTime::createFromFormat('Y-m-d', $data['endDate']) : null,
            $data['status'] ?? null
        );

        if ($report['success']) {
            $excelService = new ExcelExportService($data['generatedBy'] ?? 'System Administrator');
            $filepath = $excelService->generateBorrowingReport($report['data'], $report['summary']);

            $http->response([
                'success' => true,
                'filepath' => $filepath,
                'filename' => basename($filepath),
                'message' => 'Report exported successfully'
            ]);
        } else {
            $http->response($report);
        }
    }

    // Export return report to Excel
    elseif ($endpoint === 'export-return-excel' && $method === 'POST') {
        if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            $http->response([
                'success' => false,
                'message' => 'PhpSpreadsheet library not installed'
            ]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $reportService = new ReportService();
        $report = $reportService->getReturnReport(
            isset($data['startDate']) ? \DateTime::createFromFormat('Y-m-d', $data['startDate']) : null,
            isset($data['endDate']) ? \DateTime::createFromFormat('Y-m-d', $data['endDate']) : null
        );

        if ($report['success']) {
            $excelService = new ExcelExportService($data['generatedBy'] ?? 'System Administrator');
            $filepath = $excelService->generateReturnReport($report['data'], $report['summary']);

            $http->response([
                'success' => true,
                'filepath' => $filepath,
                'filename' => basename($filepath),
                'message' => 'Report exported successfully'
            ]);
        } else {
            $http->response($report);
        }
    }

    // Export inventory report to Excel
    elseif ($endpoint === 'export-inventory-excel' && $method === 'POST') {
        if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            $http->response([
                'success' => false,
                'message' => 'PhpSpreadsheet library not installed'
            ]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $countId = (int) ($data['countId'] ?? 0);

        $reportService = new ReportService();
        $report = $reportService->getInventoryReport($countId);

        if ($report['success']) {
            $excelService = new ExcelExportService($data['generatedBy'] ?? 'System Administrator');
            $filepath = $excelService->generateInventoryReport(
                $report['header'],
                $report['data'],
                $report['summary']
            );

            $http->response([
                'success' => true,
                'filepath' => $filepath,
                'filename' => basename($filepath),
                'message' => 'Report exported successfully'
            ]);
        } else {
            $http->response($report);
        }
    }

    else {
        $http->response([
            'success' => false,
            'message' => 'Endpoint not found'
        ], 404);
    }
} catch (Exception $e) {
    $http->response([
        'success' => false,
        'message' => $e->getMessage()
    ], 500);
}

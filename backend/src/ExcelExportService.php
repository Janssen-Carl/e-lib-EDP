<?php

declare(strict_types=1);

namespace Backend;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\BarChart;
use PhpOffice\PhpSpreadsheet\Chart\PieChart;

/**
 * ExcelExportService
 * Generates professional Excel reports with headers, logos, and charts
 * 
 * Requires: composer require phpoffice/phpspreadsheet
 */
final class ExcelExportService
{
    private Spreadsheet $spreadsheet;
    private string $companyName = 'Metro City e-Library System';
    private string $companySubtitle = 'Professional Library Management';
    private string $reportGeneratedBy;

    public function __construct(string $reportGeneratedBy = 'System Administrator')
    {
        $this->spreadsheet = new Spreadsheet();
        $this->reportGeneratedBy = $reportGeneratedBy;
    }

    /**
     * Generate borrowing report Excel file
     */
    public function generateBorrowingReport(array $data, array $summary): string
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->setTitle('Borrowing Report');

        $row = 1;
        $row = $this->addReportHeader($sheet, $row, 'Book Borrowing Transactions Report');
        $row += 1;

        // Summary section
        $this->addSummarySection($sheet, $row, $summary);
        $row += 6;

        // Data headers
        $headers = [
            'Transaction ID',
            'Member Name',
            'Email',
            'Book Title',
            'ISBN',
            'Category',
            'Borrow Date',
            'Due Date',
            'Return Date',
            'Status',
            'Days Overdue',
            'Estimated Fine'
        ];

        $this->addTableHeaders($sheet, $row, $headers);
        $row++;

        // Add data rows
        foreach ($data as $record) {
            $sheet->setCellValue("A{$row}", $record['TransactionID'] ?? '');
            $sheet->setCellValue("B{$row}", $record['MemberName'] ?? '');
            $sheet->setCellValue("C{$row}", $record['MemberEmail'] ?? '');
            $sheet->setCellValue("D{$row}", $record['BookTitle'] ?? '');
            $sheet->setCellValue("E{$row}", $record['ISBN'] ?? '');
            $sheet->setCellValue("F{$row}", $record['Category_Name'] ?? '');
            $sheet->setCellValue("G{$row}", $record['BorrowDate'] ?? '');
            $sheet->setCellValue("H{$row}", $record['DueDate'] ?? '');
            $sheet->setCellValue("I{$row}", $record['ReturnDate'] ?? '');
            $sheet->setCellValue("J{$row}", $record['Status'] ?? '');
            $sheet->setCellValue("K{$row}", $record['DaysOverdue'] ?? 0);
            $sheet->setCellValue("L{$row}", $record['EstimatedFine'] ?? 0);
            $this->formatDataRow($sheet, $row);
            $row++;
        }

        // Add signature placeholder
        $row += 2;
        $this->addSignaturePlaceholder($sheet, $row, 'Borrowing Report');

        // Create second sheet with charts
        $this->createChartSheet($this->spreadsheet, $data, 'Borrowing Analysis', $summary);

        // Auto-fit columns
        foreach (range('A', 'L') as $col) {
            $this->spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        return $this->saveAndGetPath('borrowing_report_' . date('Y-m-d_H-i-s'));
    }

    /**
     * Generate return report Excel file
     */
    public function generateReturnReport(array $data, array $summary): string
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->setTitle('Return Report');

        $row = 1;
        $row = $this->addReportHeader($sheet, $row, 'Book Return Transactions Report');
        $row += 1;

        // Summary section
        $this->addReturnSummarySection($sheet, $row, $summary);
        $row += 6;

        // Data headers
        $headers = [
            'Return Transaction ID',
            'Member Name',
            'Email',
            'Book Title',
            'ISBN',
            'Return Date',
            'Book Condition',
            'Days Overdue',
            'Late Fee',
            'Processed By',
            'Notes'
        ];

        $this->addTableHeaders($sheet, $row, $headers);
        $row++;

        // Add data rows
        foreach ($data as $record) {
            $sheet->setCellValue("A{$row}", $record['ReturnTransactionID'] ?? '');
            $sheet->setCellValue("B{$row}", $record['MemberName'] ?? '');
            $sheet->setCellValue("C{$row}", $record['MemberEmail'] ?? '');
            $sheet->setCellValue("D{$row}", $record['BookTitle'] ?? '');
            $sheet->setCellValue("E{$row}", $record['ISBN'] ?? '');
            $sheet->setCellValue("F{$row}", $record['ReturnDate'] ?? '');
            $sheet->setCellValue("G{$row}", $record['BookCondition'] ?? '');
            $sheet->setCellValue("H{$row}", $record['DaysOverdue'] ?? 0);
            $sheet->setCellValue("I{$row}", $record['LateFee'] ?? 0);
            $sheet->setCellValue("J{$row}", $record['LibrarianName'] ?? '');
            $sheet->setCellValue("K{$row}", $record['Notes'] ?? '');
            $this->formatDataRow($sheet, $row);
            $row++;
        }

        // Add signature placeholder
        $row += 2;
        $this->addSignaturePlaceholder($sheet, $row, 'Return Report');

        // Create second sheet with charts
        $this->createReturnChartSheet($this->spreadsheet, $data, $summary);

        // Auto-fit columns
        foreach (range('A', 'K') as $col) {
            $this->spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        return $this->saveAndGetPath('return_report_' . date('Y-m-d_H-i-s'));
    }

    /**
     * Generate inventory count report Excel file
     */
    public function generateInventoryReport(array $header, array $data, array $summary): string
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->setTitle('Inventory Report');

        $row = 1;
        $row = $this->addReportHeader($sheet, $row, 'Physical Inventory Count Report');
        $row += 1;

        // Inventory header info
        $sheet->setCellValue("A{$row}", 'Count Date:');
        $sheet->setCellValue("B{$row}", $header['CountDate'] ?? '');
        $sheet->setCellValue("D{$row}", 'Count Status:');
        $sheet->setCellValue("E{$row}", $header['CountStatus'] ?? '');
        $row++;

        $sheet->setCellValue("A{$row}", 'Conducted By:');
        $sheet->setCellValue("B{$row}", $header['ConductedByName'] ?? '');
        $sheet->setCellValue("D{$row}", 'Verified By:');
        $sheet->setCellValue("E{$row}", $header['VerifiedByName'] ?? '');
        $row++;

        $row += 1;

        // Summary section
        $this->addInventorySummarySection($sheet, $row, $header, $summary);
        $row += 6;

        // Data headers
        $headers = [
            'Book ID',
            'Book Title',
            'ISBN',
            'Author',
            'Category',
            'Expected Qty',
            'Physical Qty',
            'Variance',
            'Notes'
        ];

        $this->addTableHeaders($sheet, $row, $headers);
        $row++;

        // Add data rows
        foreach ($data as $record) {
            $sheet->setCellValue("A{$row}", $record['BookID'] ?? '');
            $sheet->setCellValue("B{$row}", $record['BookTitle'] ?? '');
            $sheet->setCellValue("C{$row}", $record['ISBN'] ?? '');
            $sheet->setCellValue("D{$row}", $record['Author'] ?? '');
            $sheet->setCellValue("E{$row}", $record['Category_Name'] ?? '');
            $sheet->setCellValue("F{$row}", $record['ExpectedQuantity'] ?? 0);
            $sheet->setCellValue("G{$row}", $record['PhysicalQuantity'] ?? 0);
            
            $variance = ($record['PhysicalQuantity'] ?? 0) - ($record['ExpectedQuantity'] ?? 0);
            $sheet->setCellValue("H{$row}", $variance);

            // Color code variance
            if ($variance != 0) {
                $this->spreadsheet->getActiveSheet()->getStyle("H{$row}")
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFFFF00'); // Yellow
            }

            $sheet->setCellValue("I{$row}", $record['Notes'] ?? '');
            $this->formatDataRow($sheet, $row);
            $row++;
        }

        // Add signature placeholder
        $row += 2;
        $this->addSignaturePlaceholder($sheet, $row, 'Inventory Count Report');

        // Create second sheet with charts
        $this->createInventoryChartSheet($this->spreadsheet, $data, $summary);

        // Auto-fit columns
        foreach (range('A', 'I') as $col) {
            $this->spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        return $this->saveAndGetPath('inventory_report_' . date('Y-m-d_H-i-s'));
    }

    /**
     * Add report header with company info
     */
    private function addReportHeader(&$sheet, int $row, string $title): int
    {
        // Company name
        $sheet->setCellValue("A{$row}", $this->companyName);
        $sheet->getStyle("A{$row}")->getFont()
            ->setSize(18)
            ->setBold(true)
            ->setColor(new Color('FFFFFF'));
        $sheet->getStyle("A{$row}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF2563EB'); // Blue
        $sheet->mergeCells("A{$row}:L{$row}");
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;

        // Subtitle
        $sheet->setCellValue("A{$row}", $this->companySubtitle);
        $sheet->getStyle("A{$row}")->getFont()
            ->setSize(11)
            ->setItalic(true)
            ->setColor(new Color('FFFFFF'));
        $sheet->getStyle("A{$row}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF1D4ED8'); // Dark Blue
        $sheet->mergeCells("A{$row}:L{$row}");
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;

        // Report title
        $sheet->setCellValue("A{$row}", $title);
        $sheet->getStyle("A{$row}")->getFont()
            ->setSize(14)
            ->setBold(true);
        $sheet->mergeCells("A{$row}:L{$row}");
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;

        // Generated date and time
        $sheet->setCellValue("A{$row}", 'Generated: ' . date('Y-m-d H:i:s'));
        $sheet->getStyle("A{$row}")->getFont()->setSize(10);
        $sheet->mergeCells("A{$row}:L{$row}");
        $row++;

        return $row;
    }

    /**
     * Add summary section for borrowing
     */
    private function addSummarySection(&$sheet, int $row, array $summary): void
    {
        $sheet->setCellValue("A{$row}", 'SUMMARY');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(12);
        $sheet->mergeCells("A{$row}:D{$row}");
        $row++;

        $summaryData = [
            'Total Borrowings: ' => $summary['total'] ?? 0,
            'Active Borrowings: ' => $summary['active'] ?? 0,
            'Overdue Borrowings: ' => $summary['overdue'] ?? 0,
            'Returned: ' => $summary['returned'] ?? 0,
            'Total Estimated Fines: ' => '₱' . number_format($summary['totalEstimatedFines'] ?? 0, 2),
        ];

        foreach ($summaryData as $label => $value) {
            $sheet->setCellValue("A{$row}", $label);
            $sheet->setCellValue("B{$row}", $value);
            $sheet->getStyle("A{$row}:B{$row}")->getFont()->setSize(11);
            $row++;
        }
    }

    /**
     * Add summary section for returns
     */
    private function addReturnSummarySection(&$sheet, int $row, array $summary): void
    {
        $sheet->setCellValue("A{$row}", 'SUMMARY');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(12);
        $sheet->mergeCells("A{$row}:D{$row}");
        $row++;

        $summaryData = [
            'Total Returns: ' => $summary['total'] ?? 0,
            'Good Condition: ' => $summary['good'] ?? 0,
            'Minor Damage: ' => $summary['minorDamage'] ?? 0,
            'Major Damage: ' => $summary['majorDamage'] ?? 0,
            'Lost Books: ' => $summary['lost'] ?? 0,
            'Total Late Fees Collected: ' => '₱' . number_format($summary['totalLateFees'] ?? 0, 2),
        ];

        foreach ($summaryData as $label => $value) {
            $sheet->setCellValue("A{$row}", $label);
            $sheet->setCellValue("B{$row}", $value);
            $sheet->getStyle("A{$row}:B{$row}")->getFont()->setSize(11);
            $row++;
        }
    }

    /**
     * Add summary section for inventory
     */
    private function addInventorySummarySection(&$sheet, int $row, array $header, array $summary): void
    {
        $sheet->setCellValue("A{$row}", 'SUMMARY');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(12);
        $sheet->mergeCells("A{$row}:D{$row}");
        $row++;

        $summaryData = [
            'Total Books Expected: ' => $header['TotalBooksExpected'] ?? 0,
            'Total Books Found: ' => $header['TotalBooksFound'] ?? 0,
            'Discrepancies: ' => $header['Discrepancies'] ?? 0,
            'Perfect Matches: ' => $summary['perfectMatch'] ?? 0,
            'Items with Variance: ' => $summary['discrepancies'] ?? 0,
        ];

        foreach ($summaryData as $label => $value) {
            $sheet->setCellValue("A{$row}", $label);
            $sheet->setCellValue("B{$row}", $value);
            $sheet->getStyle("A{$row}:B{$row}")->getFont()->setSize(11);
            $row++;
        }
    }

    /**
     * Add table headers with formatting
     */
    private function addTableHeaders(&$sheet, int $row, array $headers): void
    {
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue("{$col}{$row}", $header);
            $sheet->getStyle("{$col}{$row}")
                ->getFont()
                ->setBold(true)
                ->setColor(new Color('FFFFFF'));
            $sheet->getStyle("{$col}{$row}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF2563EB'); // Blue header
            $sheet->getStyle("{$col}{$row}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $col++;
        }
    }

    /**
     * Format data row with borders
     */
    private function formatDataRow(&$sheet, int $row): void
    {
        $sheet->getStyle("A{$row}:L{$row}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);
    }

    /**
     * Add signature placeholder
     */
    private function addSignaturePlaceholder(&$sheet, int $row, string $reportType): void
    {
        $sheet->setCellValue("A{$row}", 'Authorized Signature:');
        $sheet->setCellValue("A" . ($row + 1), '_' . str_repeat('_', 30));
        $sheet->setCellValue("A" . ($row + 2), 'Name/Title');

        $sheet->setCellValue("D{$row}", 'Verified By:');
        $sheet->setCellValue("D" . ($row + 1), '_' . str_repeat('_', 30));
        $sheet->setCellValue("D" . ($row + 2), 'Librarian Name/Signature');

        $sheet->setCellValue("G{$row}", 'Date: ' . date('Y-m-d'));
    }

    /**
     * Create chart sheet for borrowing data
     */
    private function createChartSheet(&$spreadsheet, array $data, string $sheetName, array $summary): void
    {
        $chartSheet = $spreadsheet->createSheet();
        $chartSheet->setTitle($sheetName);

        // Add summary data for charting
        $row = 1;
        $chartSheet->setCellValue("A{$row}", 'Status');
        $chartSheet->setCellValue("B{$row}", 'Count');
        $row++;

        $statusData = [
            'Active' => $summary['active'] ?? 0,
            'Overdue' => $summary['overdue'] ?? 0,
            'Returned' => $summary['returned'] ?? 0,
        ];

        foreach ($statusData as $status => $count) {
            $chartSheet->setCellValue("A{$row}", $status);
            $chartSheet->setCellValue("B{$row}", $count);
            $row++;
        }

        // Create pie chart
        $chart = new PieChart();
        $chart->setTitle('Borrowing Status Distribution');
        $chart->setDataFromArray([
            'labels' => ['A2:A4'],
            'values' => ['B2:B4'],
        ]);
        $chart->setResizeCoordinates('A6:G20');
        $chartSheet->addChart($chart);

        // Add category analysis
        $categoryData = [];
        foreach ($data as $record) {
            $cat = $record['Category_Name'] ?? 'Unknown';
            $categoryData[$cat] = ($categoryData[$cat] ?? 0) + 1;
        }

        $row = 25;
        $chartSheet->setCellValue("A{$row}", 'Category');
        $chartSheet->setCellValue("B{$row}", 'Borrows');
        $row++;

        $startRow = $row;
        foreach (array_slice($categoryData, 0, 10) as $cat => $count) {
            $chartSheet->setCellValue("A{$row}", $cat);
            $chartSheet->setCellValue("B{$row}", $count);
            $row++;
        }
        $endRow = $row - 1;

        // Create bar chart for categories
        $barChart = new BarChart();
        $barChart->setTitle('Top Categories by Borrowing');
        $barChart->setDataFromArray([
            'labels' => ["A" . $startRow . ":A" . $endRow],
            'values' => ["B" . $startRow . ":B" . $endRow],
        ]);
        $barChart->setResizeCoordinates('A21:G32');
        $chartSheet->addChart($barChart);
    }

    /**
     * Create chart sheet for return data
     */
    private function createReturnChartSheet(&$spreadsheet, array $data, array $summary): void
    {
        $chartSheet = $spreadsheet->createSheet();
        $chartSheet->setTitle('Return Analysis');

        // Add condition data for charting
        $row = 1;
        $chartSheet->setCellValue("A{$row}", 'Condition');
        $chartSheet->setCellValue("B{$row}", 'Count');
        $row++;

        $conditionData = [
            'Good' => $summary['good'] ?? 0,
            'Minor Damage' => $summary['minorDamage'] ?? 0,
            'Major Damage' => $summary['majorDamage'] ?? 0,
            'Lost' => $summary['lost'] ?? 0,
        ];

        foreach ($conditionData as $condition => $count) {
            $chartSheet->setCellValue("A{$row}", $condition);
            $chartSheet->setCellValue("B{$row}", $count);
            $row++;
        }

        // Create pie chart
        $chart = new PieChart();
        $chart->setTitle('Book Condition Distribution');
        $chart->setDataFromArray([
            'labels' => ['A2:A5'],
            'values' => ['B2:B5'],
        ]);
        $chart->setResizeCoordinates('A7:G22');
        $chartSheet->addChart($chart);
    }

    /**
     * Create chart sheet for inventory data
     */
    private function createInventoryChartSheet(&$spreadsheet, array $data, array $summary): void
    {
        $chartSheet = $spreadsheet->createSheet();
        $chartSheet->setTitle('Inventory Analysis');

        // Variance distribution
        $row = 1;
        $chartSheet->setCellValue("A{$row}", 'Variance Status');
        $chartSheet->setCellValue("B{$row}", 'Count');
        $row++;

        $varianceData = [
            'Perfect Match' => $summary['perfectMatch'] ?? 0,
            'With Discrepancy' => $summary['discrepancies'] ?? 0,
        ];

        foreach ($varianceData as $status => $count) {
            $chartSheet->setCellValue("A{$row}", $status);
            $chartSheet->setCellValue("B{$row}", $count);
            $row++;
        }

        // Create pie chart
        $chart = new PieChart();
        $chart->setTitle('Inventory Count Accuracy');
        $chart->setDataFromArray([
            'labels' => ['A2:A3'],
            'values' => ['B2:B3'],
        ]);
        $chart->setResizeCoordinates('A5:G20');
        $chartSheet->addChart($chart);
    }

    /**
     * Save spreadsheet and return file path
     */
    private function saveAndGetPath(string $filename): string
    {
        $filepath = $_SERVER['DOCUMENT_ROOT'] . '/reports/' . $filename . '.xlsx';
        
        // Create reports directory if not exists
        @mkdir(dirname($filepath), 0755, true);

        $writer = new Xlsx($this->spreadsheet);
        $writer->save($filepath);

        return $filepath;
    }

    /**
     * Get file for download
     */
    public function getFileForDownload(string $filepath): void
    {
        if (!file_exists($filepath)) {
            throw new \Exception('Report file not found');
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Content-Length: ' . filesize($filepath));

        readfile($filepath);
    }
}

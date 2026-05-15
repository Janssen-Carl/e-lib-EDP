/**
 * Reports Module - Handles all report generation and display
 */

document.addEventListener('DOMContentLoaded', () => {
  // Set default date range (last 30 days)
  const today = new Date();
  const thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
  
  const dateFromInput = document.getElementById('dateFrom');
  const dateToInput = document.getElementById('dateTo');
  
  if (dateFromInput) dateFromInput.valueAsDate = thirtyDaysAgo;
  if (dateToInput) dateToInput.valueAsDate = today;
});

async function loadReport() {
  const dateFrom = document.getElementById('dateFrom')?.value;
  const dateTo = document.getElementById('dateTo')?.value;
  const reportTable = document.getElementById('reportTable');

  if (!reportTable) return;

  const filters = {};
  if (dateFrom) filters.dateFrom = dateFrom;
  if (dateTo) filters.dateTo = dateTo;

  try {
    // Determine report type from current page
    const reportType = determineReportType();
    
    let response;
    switch(reportType) {
      case 'borrowing':
        response = await API.getBorrowingReport(filters);
        break;
      case 'returns':
        response = await API.getReturnReport(filters);
        break;
      case 'inventory':
        response = await API.getInventoryReport();
        break;
      default:
        response = { success: false };
    }

    if (response.success && response.data) {
      displayReport(response.data, reportType);
    } else {
      TableHelper.renderEmpty(reportTable, 7, 'No data found for the specified period');
    }
  } catch (error) {
    console.error('Error loading report:', error);
    showAlert('Error loading report', 'error');
  }
}

function determineReportType() {
  const url = window.location.pathname;
  if (url.includes('borrowing-report')) return 'borrowing';
  if (url.includes('return-report')) return 'returns';
  if (url.includes('inventory-report')) return 'inventory';
  return 'borrowing';
}

function displayReport(data, reportType) {
  const reportTable = document.getElementById('reportTable');
  
  if (!data || data.length === 0) {
    TableHelper.renderEmpty(reportTable, 7, 'No data found');
    return;
  }

  let rows;
  switch(reportType) {
    case 'borrowing':
      rows = data.map(item => `
        <td class="px-6 py-4 text-sm text-gray-900">${item.transactionId}</td>
        <td class="px-6 py-4 text-sm text-gray-900">${item.memberName}</td>
        <td class="px-6 py-4 text-sm text-gray-600">${item.bookTitle}</td>
        <td class="px-6 py-4 text-sm text-gray-600">${formatDate(item.borrowDate)}</td>
        <td class="px-6 py-4 text-sm text-gray-600">${formatDate(item.dueDate)}</td>
        <td class="px-6 py-4 text-sm text-gray-600">${formatDate(item.returnDate) || 'Not returned'}</td>
        <td class="px-6 py-4 text-sm"><span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">${item.status}</span></td>
      `);
      break;
    
    case 'returns':
      rows = data.map(item => `
        <td class="px-6 py-4 text-sm text-gray-900">${item.transactionId}</td>
        <td class="px-6 py-4 text-sm text-gray-900">${item.memberName}</td>
        <td class="px-6 py-4 text-sm text-gray-600">${item.bookTitle}</td>
        <td class="px-6 py-4 text-sm text-gray-600">${formatDate(item.returnDate)}</td>
        <td class="px-6 py-4 text-sm"><span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">${item.condition}</span></td>
        <td class="px-6 py-4 text-sm text-gray-600">${item.notes || '-'}</td>
      `);
      break;
    
    case 'inventory':
      rows = data.map(item => `
        <td class="px-6 py-4 text-sm text-gray-900">${item.title}</td>
        <td class="px-6 py-4 text-sm text-gray-600">${item.isbn || 'N/A'}</td>
        <td class="px-6 py-4 text-sm text-gray-600">${item.author}</td>
        <td class="px-6 py-4 text-sm font-semibold">${item.totalCopies}</td>
        <td class="px-6 py-4 text-sm"><span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">${item.available}</span></td>
        <td class="px-6 py-4 text-sm"><span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">${item.borrowed}</span></td>
        <td class="px-6 py-4 text-sm"><span class="px-3 py-1 rounded-full text-xs font-semibold ${item.available > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${item.available > 0 ? 'In Stock' : 'Out of Stock'}</span></td>
      `);
      break;
  }

  TableHelper.renderRows(reportTable, rows);
}

function formatDate(dateString) {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString();
}

async function exportReport() {
  const dateFrom = document.getElementById('dateFrom')?.value;
  const dateTo = document.getElementById('dateTo')?.value;
  
  const reportType = determineReportType();
  const filters = {};
  
  if (dateFrom) filters.dateFrom = dateFrom;
  if (dateTo) filters.dateTo = dateTo;

  try {
    await API.exportReport(reportType, filters);
  } catch (error) {
    console.error('Error exporting report:', error);
    showAlert('Error exporting report', 'error');
  }
}

function showAlert(message, type) {
  const container = document.getElementById('alertContainer');
  if (container) {
    AlertHelper.showAlert(container, message, type);
  }
}

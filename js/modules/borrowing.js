/**
 * Borrowing Transactions Module
 */

document.addEventListener('DOMContentLoaded', () => {
  loadBorrowingData();
  setupModalListeners();
});

function setupModalListeners() {
  // Borrow Modal
  ModalHelper.closeOnBackdropClick('borrowModal');
  ModalHelper.closeOnBackdropClick('returnModal');
}

async function loadBorrowingData() {
  try {
    const response = await API.getBorrowingTransactions();
    
    if (response.success && response.data) {
      displayBorrowingData(response.data);
      updateBorrowingStats(response.data);
    }
  } catch (error) {
    console.error('Error loading borrowing data:', error);
    showAlert('Error loading borrowing transactions', 'error');
  }
}

function displayBorrowingData(transactions) {
  const tbody = document.getElementById('tableBody');
  
  if (!transactions || transactions.length === 0) {
    TableHelper.renderEmpty(tbody, 8, 'No borrowing transactions found');
    return;
  }

  const rows = transactions.map(tx => `
    <td class="px-6 py-4 text-sm text-gray-900">${tx.id || 'N/A'}</td>
    <td class="px-6 py-4 text-sm text-gray-900">${tx.memberName || 'Unknown'}</td>
    <td class="px-6 py-4 text-sm text-gray-900">${tx.bookTitle || 'Unknown'}</td>
    <td class="px-6 py-4 text-sm text-gray-600">${tx.isbn || 'N/A'}</td>
    <td class="px-6 py-4 text-sm text-gray-600">${formatDate(tx.borrowDate)}</td>
    <td class="px-6 py-4 text-sm text-gray-600">${formatDate(tx.dueDate)}</td>
    <td class="px-6 py-4 text-sm">
      <span class="px-3 py-1 rounded-full text-xs font-semibold ${getStatusBadgeClass(tx.status)}">
        ${tx.status}
      </span>
    </td>
    <td class="px-6 py-4 text-sm">
      ${tx.status === 'Active' ? `
        <button onclick="openReturnModal('${tx.id}', '${tx.bookTitle}')" class="text-blue-600 hover:text-blue-700 font-medium">
          Return
        </button>
      ` : '-'}
    </td>
  `);

  TableHelper.renderRows(tbody, rows);
}

function updateBorrowingStats(transactions) {
  const stats = {
    total: transactions.length,
    active: transactions.filter(t => t.status === 'Active').length,
    overdue: transactions.filter(t => t.status === 'Overdue').length,
    returned: transactions.filter(t => t.status === 'Returned').length,
  };

  document.getElementById('statTotal').textContent = stats.total;
  document.getElementById('statActive').textContent = stats.active;
  document.getElementById('statOverdue').textContent = stats.overdue;
  document.getElementById('statReturned').textContent = stats.returned;
}

function getStatusBadgeClass(status) {
  switch(status) {
    case 'Active':
      return 'bg-green-100 text-green-800';
    case 'Overdue':
      return 'bg-red-100 text-red-800';
    case 'Returned':
      return 'bg-gray-100 text-gray-800';
    default:
      return 'bg-blue-100 text-blue-800';
  }
}

function formatDate(dateString) {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString();
}

function openBorrowModal() {
  ModalHelper.open('borrowModal');
  loadBooksAndMembers();
}

function closeBorrowModal() {
  ModalHelper.close('borrowModal');
}

function openReturnModal(transactionId, bookTitle) {
  ModalHelper.open('returnModal');
  document.getElementById('returnTransactionId').value = transactionId;
  document.getElementById('returnBookTitle').value = bookTitle;
}

function closeReturnModal() {
  ModalHelper.close('returnModal');
}

async function loadBooksAndMembers() {
  try {
    const booksResponse = await API.getInventory();
    const usersResponse = await API.getAllUsers();

    if (booksResponse.success && booksResponse.data) {
      populateBookSelect(booksResponse.data);
    }

    if (usersResponse.success && usersResponse.data) {
      populateMemberSelect(usersResponse.data);
    }
  } catch (error) {
    console.error('Error loading books and members:', error);
  }
}

function populateBookSelect(books) {
  const select = document.getElementById('borrowBookId');
  select.innerHTML = '<option value="">-- Select a book --</option>';
  
  books.forEach(book => {
    const option = document.createElement('option');
    option.value = book.id;
    option.textContent = `${book.title} (${book.available} available)`;
    select.appendChild(option);
  });
}

function populateMemberSelect(users) {
  const select = document.getElementById('borrowUserId');
  select.innerHTML = '<option value="">-- Select a member --</option>';
  
  users.forEach(user => {
    const option = document.createElement('option');
    option.value = user.id;
    option.textContent = `${user.name} (${user.email})`;
    select.appendChild(option);
  });
}

async function submitBorrowForm() {
  const bookId = document.getElementById('borrowBookId').value;
  const userId = document.getElementById('borrowUserId').value;
  const dueDays = parseInt(document.getElementById('borrowDueDays').value) || 14;
  const notes = document.getElementById('borrowNotes').value;

  if (!bookId || !userId) {
    showFormAlert('borrowFormAlert', 'Please select both a book and member', 'error');
    return;
  }

  try {
    const response = await API.createBorrowingTransaction({
      bookId,
      userId,
      dueDays,
      notes,
    });

    if (response.success) {
      showFormAlert('borrowFormAlert', 'Borrowing transaction created successfully!', 'success');
      closeBorrowModal();
      loadBorrowingData();
    } else {
      showFormAlert('borrowFormAlert', response.message || 'Failed to create borrowing', 'error');
    }
  } catch (error) {
    console.error('Error:', error);
    showFormAlert('borrowFormAlert', 'An error occurred', 'error');
  }
}

async function submitReturnForm() {
  const transactionId = document.getElementById('returnTransactionId').value;
  const condition = document.getElementById('returnBookCondition').value;
  const notes = document.getElementById('returnNotes').value;

  try {
    const response = await API.returnBook(transactionId, { condition, notes });

    if (response.success) {
      showFormAlert('returnFormAlert', 'Book returned successfully!', 'success');
      closeReturnModal();
      loadBorrowingData();
    } else {
      showFormAlert('returnFormAlert', response.message || 'Failed to process return', 'error');
    }
  } catch (error) {
    console.error('Error:', error);
    showFormAlert('returnFormAlert', 'An error occurred', 'error');
  }
}

function filterByStatus(status) {
  // Placeholder - implement filtering logic
  console.log('Filter by status:', status);
}

function showFormAlert(containerId, message, type) {
  const container = document.getElementById(containerId);
  AlertHelper.showAlert(container, message, type);
}

function showAlert(message, type) {
  const container = document.getElementById('alertContainer');
  AlertHelper.showAlert(container, message, type);
}

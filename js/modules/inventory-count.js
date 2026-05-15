/**
 * Inventory Count Module
 */

document.addEventListener('DOMContentLoaded', () => {
  loadInventoryData();
  const form = document.getElementById('addBookForm');
  if (form) {
    form.addEventListener('submit', handleAddBook);
  }
  ModalHelper.closeOnBackdropClick('addBookModal');
});

async function loadInventoryData() {
  try {
    const response = await API.getInventory();
    
    if (response.success && response.data) {
      displayInventoryData(response.data);
      updateInventoryStats(response.data);
    }
  } catch (error) {
    console.error('Error loading inventory:', error);
    showAlert('Error loading inventory', 'error');
  }
}

function displayInventoryData(books) {
  const tbody = document.getElementById('tableBody');
  
  if (!books || books.length === 0) {
    TableHelper.renderEmpty(tbody, 7, 'No books in inventory');
    return;
  }

  const rows = books.map(book => `
    <td class="px-6 py-4 text-sm text-gray-900">${book.title}</td>
    <td class="px-6 py-4 text-sm text-gray-600">${book.isbn || 'N/A'}</td>
    <td class="px-6 py-4 text-sm text-gray-600">${book.author}</td>
    <td class="px-6 py-4 text-sm font-semibold text-gray-900">${book.totalCopies}</td>
    <td class="px-6 py-4 text-sm"><span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">${book.available}</span></td>
    <td class="px-6 py-4 text-sm"><span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">${book.borrowed}</span></td>
    <td class="px-6 py-4 text-sm">
      <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Edit</a>
    </td>
  `);

  TableHelper.renderRows(tbody, rows);
}

function updateInventoryStats(books) {
  const stats = {
    total: books.length,
    available: books.reduce((sum, b) => sum + b.available, 0),
    borrowed: books.reduce((sum, b) => sum + b.borrowed, 0),
    damaged: books.reduce((sum, b) => sum + (b.damaged || 0), 0),
  };

  document.getElementById('statTotal').textContent = stats.total;
  document.getElementById('statAvailable').textContent = stats.available;
  document.getElementById('statBorrowed').textContent = stats.borrowed;
  document.getElementById('statDamaged').textContent = stats.damaged;
}

function openAddBookModal() {
  ModalHelper.open('addBookModal');
}

function closeAddBookModal() {
  ModalHelper.close('addBookModal');
}

async function handleAddBook(e) {
  e.preventDefault();

  const bookData = {
    title: document.getElementById('bookTitle').value.trim(),
    author: document.getElementById('bookAuthor').value.trim(),
    isbn: document.getElementById('bookISBN').value.trim(),
    quantity: parseInt(document.getElementById('bookQuantity').value),
  };

  if (!bookData.title || !bookData.author || !bookData.quantity) {
    showFormAlert('bookFormAlert', 'Title, author, and quantity are required', 'error');
    return;
  }

  try {
    const response = await API.addBook(bookData);

    if (response.success) {
      showFormAlert('bookFormAlert', 'Book added successfully!', 'success');
      document.getElementById('addBookForm').reset();
      setTimeout(() => {
        closeAddBookModal();
        loadInventoryData();
      }, 1000);
    } else {
      showFormAlert('bookFormAlert', response.message || 'Failed to add book', 'error');
    }
  } catch (error) {
    console.error('Error:', error);
    showFormAlert('bookFormAlert', 'An error occurred', 'error');
  }
}

function showFormAlert(containerId, message, type) {
  const container = document.getElementById(containerId);
  AlertHelper.showAlert(container, message, type);
}

function showAlert(message, type) {
  const container = document.getElementById('alertContainer');
  AlertHelper.showAlert(container, message, type);
}

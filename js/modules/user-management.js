/**
 * User Management Module
 */

document.addEventListener('DOMContentLoaded', () => {
  loadUserData();
  const form = document.getElementById('addUserForm');
  if (form) {
    form.addEventListener('submit', handleAddUser);
  }
  ModalHelper.closeOnBackdropClick('addUserModal');
});

async function loadUserData() {
  try {
    const response = await API.getAllUsers();
    
    if (response.success && response.data) {
      displayUserData(response.data);
    }
  } catch (error) {
    console.error('Error loading users:', error);
    showAlert('Error loading users', 'error');
  }
}

function displayUserData(users) {
  const tbody = document.getElementById('tableBody');
  
  if (!users || users.length === 0) {
    TableHelper.renderEmpty(tbody, 6, 'No users found');
    return;
  }

  const rows = users.map(user => `
    <td class="px-6 py-4 text-sm text-gray-900">${user.name || user.firstName + ' ' + user.lastName || 'Unknown'}</td>
    <td class="px-6 py-4 text-sm text-gray-600">${user.email}</td>
    <td class="px-6 py-4 text-sm"><span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">${user.role}</span></td>
    <td class="px-6 py-4 text-sm"><span class="px-3 py-1 rounded-full text-xs font-semibold ${user.isActive ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">${user.isActive ? 'Active' : 'Inactive'}</span></td>
    <td class="px-6 py-4 text-sm text-gray-600">${formatDate(user.createdAt)}</td>
    <td class="px-6 py-4 text-sm">
      <a href="/pages/edit-user.php?id=${user.id}" class="text-blue-600 hover:text-blue-700 font-medium">Edit</a>
      <button onclick="deleteUser('${user.id}')" class="ml-2 text-red-600 hover:text-red-700 font-medium">Delete</button>
    </td>
  `);

  TableHelper.renderRows(tbody, rows);
}

function formatDate(dateString) {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString();
}

function openAddUserModal() {
  ModalHelper.open('addUserModal');
}

function closeAddUserModal() {
  ModalHelper.close('addUserModal');
}

async function handleAddUser(e) {
  e.preventDefault();

  const userData = {
    name: document.getElementById('userName').value.trim(),
    email: document.getElementById('userEmail').value.trim(),
    role: document.getElementById('userRole').value,
    password: document.getElementById('userPassword').value,
  };

  if (!userData.name || !userData.email || !userData.password) {
    showFormAlert('addUserFormAlert', 'All fields are required', 'error');
    return;
  }

  if (!FormValidator.isValidEmail(userData.email)) {
    showFormAlert('addUserFormAlert', 'Invalid email address', 'error');
    return;
  }

  try {
    const response = await API.post('/users', userData);

    if (response.success) {
      showFormAlert('addUserFormAlert', 'User added successfully!', 'success');
      document.getElementById('addUserForm').reset();
      setTimeout(() => {
        closeAddUserModal();
        loadUserData();
      }, 1000);
    } else {
      showFormAlert('addUserFormAlert', response.message || 'Failed to add user', 'error');
    }
  } catch (error) {
    console.error('Error:', error);
    showFormAlert('addUserFormAlert', 'An error occurred', 'error');
  }
}

async function deleteUser(userId) {
  if (!confirm('Are you sure you want to delete this user?')) return;

  try {
    const response = await API.deleteUser(userId);

    if (response.success) {
      showAlert('User deleted successfully!', 'success');
      loadUserData();
    } else {
      showAlert(response.message || 'Failed to delete user', 'error');
    }
  } catch (error) {
    console.error('Error:', error);
    showAlert('An error occurred', 'error');
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

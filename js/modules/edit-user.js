/**
 * Edit User Module
 */

document.addEventListener('DOMContentLoaded', () => {
  loadUserData();
  const form = document.getElementById('editUserForm');
  if (form) {
    form.addEventListener('submit', handleSaveUser);
  }
});

async function loadUserData() {
  const params = new URLSearchParams(window.location.search);
  const userId = params.get('id');

  if (!userId) {
    showAlert('No user selected', 'error');
    return;
  }

  try {
    const response = await API.getUserById(userId);
    
    if (response.success && response.data) {
      populateUserForm(response.data);
    } else {
      showAlert('User not found', 'error');
    }
  } catch (error) {
    console.error('Error loading user:', error);
    showAlert('Error loading user', 'error');
  }
}

function populateUserForm(user) {
  const fields = ['firstName', 'lastName', 'email', 'phoneNumber', 'address', 'city', 'state', 'postalCode'];
  
  fields.forEach(field => {
    const input = document.getElementById(field);
    if (input && user[field]) {
      input.value = user[field];
    }
  });

  const roleSelect = document.getElementById('role');
  if (roleSelect && user.role) {
    roleSelect.value = user.role;
  }
}

async function handleSaveUser(e) {
  e.preventDefault();

  const params = new URLSearchParams(window.location.search);
  const userId = params.get('id');

  if (!userId) {
    showAlert('User ID not found', 'error');
    return;
  }

  const userData = {
    firstName: document.getElementById('firstName').value.trim(),
    lastName: document.getElementById('lastName').value.trim(),
    email: document.getElementById('email').value.trim(),
    role: document.getElementById('role').value,
    phoneNumber: document.getElementById('phoneNumber').value.trim(),
    address: document.getElementById('address').value.trim(),
    city: document.getElementById('city').value.trim(),
    state: document.getElementById('state').value.trim(),
    postalCode: document.getElementById('postalCode').value.trim(),
    resetPasswordOnLogin: document.getElementById('resetPassword').checked,
  };

  // Validate
  if (!userData.firstName || !userData.lastName) {
    showAlert('First and last name are required', 'error');
    return;
  }

  if (!FormValidator.isValidEmail(userData.email)) {
    showAlert('Please enter a valid email address', 'error');
    return;
  }

  try {
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = true;

    const response = await API.updateUser(userId, userData);

    if (response.success) {
      showAlert('User updated successfully!', 'success');
      setTimeout(() => window.location.href = '/pages/user-management.php', 1500);
    } else {
      showAlert(response.message || 'Failed to update user', 'error');
      submitBtn.disabled = false;
    }
  } catch (error) {
    console.error('Error updating user:', error);
    showAlert('An error occurred', 'error');
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = false;
  }
}

function showAlert(message, type) {
  const container = document.getElementById('alertContainer');
  AlertHelper.showAlert(container, message, type);
}

/**
 * Change Password Module
 */

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('changePasswordForm');
  if (form) {
    form.addEventListener('submit', handleChangePassword);
  }
});

async function handleChangePassword(e) {
  e.preventDefault();

  const currentPassword = document.getElementById('currentPassword').value;
  const newPassword = document.getElementById('newPassword').value;
  const confirmPassword = document.getElementById('confirmPassword').value;

  // Validate
  if (!currentPassword) {
    showAlert('Current password is required', 'error');
    return;
  }

  if (!FormValidator.isValidPassword(newPassword)) {
    showAlert('New password must be at least 8 characters', 'error');
    return;
  }

  if (newPassword !== confirmPassword) {
    showAlert('Passwords do not match', 'error');
    return;
  }

  try {
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = true;

    const response = await API.changePassword(currentPassword, newPassword);

    if (response.success) {
      showAlert('Password changed successfully!', 'success');
      document.getElementById('changePasswordForm').reset();
      setTimeout(() => window.location.href = '/', 1500);
    } else {
      showAlert(response.message || 'Failed to change password', 'error');
      submitBtn.disabled = false;
    }
  } catch (error) {
    console.error('Error:', error);
    showAlert('An error occurred', 'error');
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = false;
  }
}

function showAlert(message, type) {
  const container = document.getElementById('alertContainer');
  AlertHelper.showAlert(container, message, type);
}

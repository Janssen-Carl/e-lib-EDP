/**
 * Forgot Password Module
 */

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('forgotPasswordForm');
  if (form) {
    form.addEventListener('submit', handleForgotPassword);
  }
});

async function handleForgotPassword(e) {
  e.preventDefault();

  const email = document.getElementById('email').value.trim();

  if (!FormValidator.isValidEmail(email)) {
    showError('Please enter a valid email address');
    return;
  }

  try {
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

    const response = await API.requestPasswordReset(email);

    if (response.success) {
      showSuccess('Check your email for password reset instructions');
      document.getElementById('forgotPasswordForm').reset();
    } else {
      showError(response.message || 'Failed to send reset email');
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="fas fa-envelope"></i> Send Reset Link';
    }
  } catch (error) {
    console.error('Error:', error);
    showError('An error occurred. Please try again.');
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = false;
    submitBtn.innerHTML = '<i class="fas fa-envelope"></i> Send Reset Link';
  }
}

function showError(message) {
  const alertBanner = document.getElementById('alertBanner');
  const alertText = document.getElementById('alertText');
  
  if (alertText) {
    alertText.textContent = message;
  }
  
  if (alertBanner) {
    alertBanner.classList.add('hidden');
  }
}

function showSuccess(message) {
  const alertBanner = document.getElementById('alertBanner');
  const alertText = document.getElementById('alertText');
  
  if (alertBanner) {
    alertBanner.classList.add('bg-blue-50');
    alertBanner.classList.add('border-blue-200');
  }
  
  if (alertText) {
    alertText.textContent = message;
  }
  
  if (alertBanner) {
    alertBanner.classList.remove('hidden');
  }
}

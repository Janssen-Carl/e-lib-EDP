/**
 * Registration Module
 */

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('registrationForm');
  if (form) {
    form.addEventListener('submit', handleRegistration);
  }
});

async function handleRegistration(e) {
  e.preventDefault();

  const formData = {
    fullName: document.getElementById('fullName').value.trim(),
    email: document.getElementById('email').value.trim(),
    phoneNumber: document.getElementById('phoneNumber').value.trim(),
    address: document.getElementById('address').value.trim(),
    city: document.getElementById('city').value.trim(),
    postalCode: document.getElementById('postalCode').value.trim(),
    password: document.getElementById('password').value,
    confirmPassword: document.getElementById('confirmPassword').value,
  };

  // Validate
  if (!formData.fullName) {
    showError('Please enter your full name');
    return;
  }

  if (!FormValidator.isValidEmail(formData.email)) {
    showError('Please enter a valid email address');
    return;
  }

  if (!FormValidator.isValidPassword(formData.password)) {
    showError('Password must be at least 8 characters');
    return;
  }

  if (formData.password !== formData.confirmPassword) {
    showError('Passwords do not match');
    return;
  }

  if (!document.getElementById('agreeTerms').checked) {
    showError('You must agree to the Terms of Service');
    return;
  }

  try {
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating account...';

    const response = await API.register(formData);

    if (response.success) {
      // Store session
      API.setSession(response.user, response.accessToken);
      
      // Show success and redirect
      showSuccess('Account created successfully! Redirecting...');
      setTimeout(() => {
        window.location.href = '/pages/edit-profile.php';
      }, 1500);
    } else {
      showError(response.message || 'Failed to create account');
      submitBtn.disabled = false;
    }
  } catch (error) {
    console.error('Registration error:', error);
    showError('An error occurred. Please try again.');
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = false;
  }
}

function showError(message) {
  const alertBanner = document.getElementById('alertBanner');
  const alertText = document.getElementById('alertText');
  
  if (alertText) {
    alertText.textContent = message;
  }
  
  if (alertBanner) {
    alertBanner.classList.remove('hidden');
  }
}

function showSuccess(message) {
  const alertBanner = document.getElementById('alertBanner');
  const alertText = document.getElementById('alertText');
  
  if (alertBanner) {
    alertBanner.classList.remove('bg-red-50', 'border-red-200');
    alertBanner.classList.add('bg-green-50', 'border-green-200');
  }
  
  if (alertText) {
    alertText.textContent = message;
    alertText.className = 'text-green-700 text-sm font-medium';
  }
  
  if (alertBanner) {
    alertBanner.classList.remove('hidden');
  }
}

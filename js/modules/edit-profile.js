/**
 * Edit Profile Module
 */

document.addEventListener('DOMContentLoaded', () => {
  loadUserProfile();
  const form = document.getElementById('editProfileForm');
  if (form) {
    form.addEventListener('submit', handleSaveProfile);
  }
});

async function loadUserProfile() {
  try {
    const response = await API.getUserProfile();
    
    if (response.success && response.data) {
      populateProfileForm(response.data);
    }
  } catch (error) {
    console.error('Error loading profile:', error);
    showAlert('Error loading profile', 'error');
  }
}

function populateProfileForm(user) {
  const fields = ['firstName', 'lastName', 'email', 'phoneNumber', 'address', 'city', 'state', 'postalCode', 'bio'];
  
  fields.forEach(field => {
    const input = document.getElementById(field);
    if (input && user[field]) {
      input.value = user[field];
    }
  });
}

async function handleSaveProfile(e) {
  e.preventDefault();

  const formData = {
    firstName: document.getElementById('firstName').value.trim(),
    lastName: document.getElementById('lastName').value.trim(),
    email: document.getElementById('email').value.trim(),
    phoneNumber: document.getElementById('phoneNumber').value.trim(),
    address: document.getElementById('address').value.trim(),
    city: document.getElementById('city').value.trim(),
    state: document.getElementById('state').value.trim(),
    postalCode: document.getElementById('postalCode').value.trim(),
    bio: document.getElementById('bio').value.trim(),
  };

  // Validate
  if (!formData.firstName || !formData.lastName) {
    showAlert('First and last name are required', 'error');
    return;
  }

  if (!FormValidator.isValidEmail(formData.email)) {
    showAlert('Please enter a valid email address', 'error');
    return;
  }

  try {
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = true;

    const response = await API.updateUserProfile(formData);

    if (response.success) {
      showAlert('Profile updated successfully!', 'success');
      setTimeout(() => window.location.reload(), 1500);
    } else {
      showAlert(response.message || 'Failed to update profile', 'error');
      submitBtn.disabled = false;
    }
  } catch (error) {
    console.error('Error updating profile:', error);
    showAlert('An error occurred', 'error');
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = false;
  }
}

function showAlert(message, type) {
  const container = document.getElementById('alertContainer');
  AlertHelper.showAlert(container, message, type);
}

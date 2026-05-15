/**
 * Login Module
 */

let currentRole = 'Member';

function setRole(role) {
  currentRole = role;
  
  // Update button styles
  document.getElementById('btnMember').classList.toggle('active-tab');
  document.getElementById('btnLibrarian').classList.toggle('active-tab');
  document.getElementById('btnMember').classList.toggle('bg-white');
  document.getElementById('btnMember').classList.toggle('text-gray-600');
  document.getElementById('btnMember').classList.toggle('shadow-sm');
  document.getElementById('btnLibrarian').classList.toggle('bg-white');
  document.getElementById('btnLibrarian').classList.toggle('text-gray-600');
  document.getElementById('btnLibrarian').classList.toggle('shadow-sm');
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('loginForm');
  if (form) {
    form.addEventListener('submit', handleLogin);
  }

  // Setup alert banner to auto-hide
  const alertBanner = document.getElementById('alertBanner');
  if (alertBanner) {
    alertBanner.addEventListener('click', (e) => {
      if (e.target.classList.contains('close')) {
        alertBanner.classList.add('hidden');
      }
    });
  }
});

async function handleLogin(e) {
  e.preventDefault();

  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value.trim();
  const remember = document.getElementById('remember').checked;

  // Validate inputs
  if (!FormValidator.isValidEmail(email)) {
    showLoginError('Please enter a valid email address');
    return;
  }

  if (!FormValidator.isValidPassword(password)) {
    showLoginError('Password must be at least 8 characters');
    return;
  }

  try {
    // Show loading state
    const submitBtn = document.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing in...';
    submitBtn.disabled = true;

    const response = await API.login(email, password, currentRole);

    if (response.success) {
      // Store session
      API.setSession(response.user, response.accessToken);

      if (remember) {
        localStorage.setItem('rememberMe', 'true');
        localStorage.setItem('rememberedEmail', email);
      }

      // Redirect based on role
      const redirectUrl = currentRole === 'Librarian' 
        ? '/pages/borrowing-transactions.php'
        : '/pages/edit-profile.php';
      
      window.location.href = redirectUrl;
    } else {
      showLoginError(response.message || 'Invalid email or password');
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
    }
  } catch (error) {
    console.error('Login error:', error);
    showLoginError('An error occurred. Please try again.');
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = false;
  }
}

function showLoginError(message) {
  const alertBanner = document.getElementById('alertBanner');
  const alertText = document.getElementById('alertText');
  
  if (alertText) {
    alertText.textContent = message;
  }
  
  if (alertBanner) {
    alertBanner.classList.remove('hidden');
  }
}

// Pre-fill email if "Remember me" was checked
document.addEventListener('DOMContentLoaded', () => {
  const rememberedEmail = localStorage.getItem('rememberedEmail');
  if (rememberedEmail) {
    const emailInput = document.getElementById('email');
    const rememberCheckbox = document.getElementById('remember');
    
    if (emailInput) {
      emailInput.value = rememberedEmail;
    }
    if (rememberCheckbox) {
      rememberCheckbox.checked = true;
    }
  }
});

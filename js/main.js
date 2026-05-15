/**
 * Main API Utility - Central hub for all API communication
 * Handles authentication, requests, and session management
 */

const API = {
  BASE_URL: 'http://localhost:8000/api',
  ACCESS_TOKEN_KEY: 'accessToken',
  USER_KEY: 'userData',

  /**
   * Get stored access token
   */
  getAccessToken() {
    return localStorage.getItem(this.ACCESS_TOKEN_KEY);
  },

  /**
   * Get stored user data
   */
  getUser() {
    const user = localStorage.getItem(this.USER_KEY);
    return user ? JSON.parse(user) : null;
  },

  /**
   * Save tokens and user data after login
   */
  setSession(user, accessToken) {
    localStorage.setItem(this.USER_KEY, JSON.stringify(user));
    localStorage.setItem(this.ACCESS_TOKEN_KEY, accessToken);
  },

  /**
   * Clear all session data (logout)
   */
  clearSession() {
    localStorage.removeItem(this.ACCESS_TOKEN_KEY);
    localStorage.removeItem(this.USER_KEY);
  },

  /**
   * Make authenticated API request
   */
  async request(endpoint, options = {}) {
    const token = this.getAccessToken();
    
    const headers = {
      'Content-Type': 'application/json',
      ...options.headers,
    };

    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }

    const config = {
      ...options,
      headers,
    };

    try {
      const response = await fetch(`${this.BASE_URL}${endpoint}`, config);
      
      if (response.status === 401) {
        // Token expired or invalid
        this.clearSession();
        window.location.href = '/pages/login.php';
        return null;
      }

      const data = await response.json();
      
      if (!response.ok) {
        throw new Error(data.message || 'API request failed');
      }

      return data;
    } catch (error) {
      console.error('API Error:', error);
      throw error;
    }
  },

  /**
   * GET request
   */
  get(endpoint) {
    return this.request(endpoint, { method: 'GET' });
  },

  /**
   * POST request
   */
  post(endpoint, data) {
    return this.request(endpoint, {
      method: 'POST',
      body: JSON.stringify(data),
    });
  },

  /**
   * PUT request
   */
  put(endpoint, data) {
    return this.request(endpoint, {
      method: 'PUT',
      body: JSON.stringify(data),
    });
  },

  /**
   * DELETE request
   */
  delete(endpoint) {
    return this.request(endpoint, { method: 'DELETE' });
  },

  // ============ Authentication Endpoints ============

  /**
   * Login user
   */
  async login(email, password, role = 'Member') {
    return this.post('/auth/login', { email, password, role });
  },

  /**
   * Register new user
   */
  async register(userData) {
    return this.post('/auth/register', userData);
  },

  /**
   * Request password reset
   */
  async requestPasswordReset(email) {
    return this.post('/auth/forgot-password', { email });
  },

  /**
   * Reset password with token
   */
  async resetPassword(token, password) {
    return this.post('/auth/reset-password', { token, password });
  },

  /**
   * Change password (authenticated)
   */
  async changePassword(currentPassword, newPassword) {
    return this.post('/auth/change-password', { currentPassword, newPassword });
  },

  // ============ User Endpoints ============

  /**
   * Get current user profile
   */
  async getUserProfile() {
    return this.get('/users/profile');
  },

  /**
   * Update user profile
   */
  async updateUserProfile(data) {
    return this.put('/users/profile', data);
  },

  /**
   * Get all users (admin)
   */
  async getAllUsers() {
    return this.get('/users');
  },

  /**
   * Get user by ID
   */
  async getUserById(userId) {
    return this.get(`/users/${userId}`);
  },

  /**
   * Update user (admin)
   */
  async updateUser(userId, data) {
    return this.put(`/users/${userId}`, data);
  },

  /**
   * Delete user (admin)
   */
  async deleteUser(userId) {
    return this.delete(`/users/${userId}`);
  },

  // ============ Borrowing Endpoints ============

  /**
   * Get borrowing transactions
   */
  async getBorrowingTransactions() {
    return this.get('/borrowing');
  },

  /**
   * Create borrowing transaction
   */
  async createBorrowingTransaction(data) {
    return this.post('/borrowing', data);
  },

  /**
   * Return borrowed book
   */
  async returnBook(transactionId, data) {
    return this.put(`/borrowing/${transactionId}/return`, data);
  },

  // ============ Inventory Endpoints ============

  /**
   * Get inventory
   */
  async getInventory() {
    return this.get('/inventory');
  },

  /**
   * Get book by ID
   */
  async getBookById(bookId) {
    return this.get(`/inventory/${bookId}`);
  },

  /**
   * Add book to inventory
   */
  async addBook(data) {
    return this.post('/inventory', data);
  },

  /**
   * Update inventory count
   */
  async updateInventory(bookId, data) {
    return this.put(`/inventory/${bookId}`, data);
  },

  // ============ Report Endpoints ============

  /**
   * Get borrowing report
   */
  async getBorrowingReport(filters = {}) {
    const params = new URLSearchParams(filters).toString();
    return this.get(`/reports/borrowing?${params}`);
  },

  /**
   * Get return report
   */
  async getReturnReport(filters = {}) {
    const params = new URLSearchParams(filters).toString();
    return this.get(`/reports/returns?${params}`);
  },

  /**
   * Get inventory report
   */
  async getInventoryReport() {
    return this.get('/reports/inventory');
  },

  /**
   * Export report to Excel
   */
  async exportReport(reportType, filters = {}) {
    const params = new URLSearchParams(filters).toString();
    const url = `${this.BASE_URL}/reports/${reportType}/export?${params}`;
    window.open(url, '_blank');
  },
};

/**
 * Alert Helper - Display messages to user
 */
const AlertHelper = {
  showAlert(container, message, type = 'error') {
    if (!container) return;

    const alertEl = document.createElement('div');
    alertEl.className = `p-4 rounded-lg flex items-center gap-3 ${
      type === 'success' 
        ? 'bg-green-50 border border-green-200' 
        : 'bg-red-50 border border-red-200'
    }`;

    const icon = type === 'success' 
      ? '<i class="fas fa-check-circle text-green-600"></i>'
      : '<i class="fas fa-exclamation-circle text-red-600"></i>';

    const textClass = type === 'success' ? 'text-green-700' : 'text-red-700';

    alertEl.innerHTML = `
      ${icon}
      <span class="${textClass} text-sm font-medium">${message}</span>
    `;

    container.innerHTML = '';
    container.appendChild(alertEl);

    if (type === 'success') {
      setTimeout(() => alertEl.remove(), 3000);
    }
  },

  clearAlert(container) {
    if (container) {
      container.innerHTML = '';
    }
  },
};

/**
 * Form Validation Helpers
 */
const FormValidator = {
  isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
  },

  isValidPassword(password) {
    return password && password.length >= 8;
  },

  validateEmail(emailInput) {
    if (!emailInput.value) {
      this.showError(emailInput, 'Email is required');
      return false;
    }
    if (!this.isValidEmail(emailInput.value)) {
      this.showError(emailInput, 'Please enter a valid email');
      return false;
    }
    this.clearError(emailInput);
    return true;
  },

  validatePassword(passwordInput) {
    if (!passwordInput.value) {
      this.showError(passwordInput, 'Password is required');
      return false;
    }
    if (!this.isValidPassword(passwordInput.value)) {
      this.showError(passwordInput, 'Password must be at least 8 characters');
      return false;
    }
    this.clearError(passwordInput);
    return true;
  },

  showError(input, message) {
    const errorEl = input.nextElementSibling;
    if (errorEl && errorEl.classList.contains('error-msg')) {
      errorEl.textContent = message;
      errorEl.classList.add('visible');
    }
  },

  clearError(input) {
    const errorEl = input.nextElementSibling;
    if (errorEl && errorEl.classList.contains('error-msg')) {
      errorEl.classList.remove('visible');
    }
  },
};

/**
 * Table Rendering Helper
 */
const TableHelper = {
  clearTable(tbody) {
    tbody.innerHTML = '';
  },

  renderEmpty(tbody, colSpan, message = 'No data found') {
    this.clearTable(tbody);
    const row = document.createElement('tr');
    row.className = 'border-b border-gray-200';
    row.innerHTML = `
      <td colspan="${colSpan}" class="px-6 py-12 text-center text-gray-500">
        <i class="fas fa-inbox text-3xl mb-2 block"></i>
        <p>${message}</p>
      </td>
    `;
    tbody.appendChild(row);
  },

  renderRows(tbody, rows) {
    this.clearTable(tbody);
    rows.forEach(row => {
      const tr = document.createElement('tr');
      tr.className = 'border-b border-gray-200 hover:bg-gray-50';
      tr.innerHTML = row;
      tbody.appendChild(tr);
    });
  },
};

/**
 * Modal Helper
 */
const ModalHelper = {
  open(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.remove('hidden');
    }
  },

  close(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.add('hidden');
    }
  },

  closeOnBackdropClick(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          this.close(modalId);
        }
      });
    }
  },
};

// Initialize modal backdrop clicks
document.addEventListener('DOMContentLoaded', () => {
  const modals = document.querySelectorAll('[id$="Modal"]');
  modals.forEach(modal => {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.classList.add('hidden');
      }
    });
  });
});

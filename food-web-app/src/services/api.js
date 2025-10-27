// API Base URL - Update this to match your backend server
const API_BASE_URL = 'http://localhost/Food-Web-App_IE4727/food-web-app/backend/api';

// Helper function for fetch with credentials
const fetchWithCredentials = async (url, options = {}) => {
  const defaultOptions = {
    credentials: 'include', // Include cookies for session management
    headers: {
      'Content-Type': 'application/json',
      ...options.headers,
    },
    ...options,
  };

  try {
    const response = await fetch(url, defaultOptions);
    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.error || 'Request failed');
    }

    return data;
  } catch (error) {
    console.error('API Error:', error);
    throw error;
  }
};

// Authentication API
export const authAPI = {
  // Login user
  login: async (credentials) => {
    return fetchWithCredentials(`${API_BASE_URL}/auth/login.php`, {
      method: 'POST',
      body: JSON.stringify(credentials),
    });
  },

  // Register new user
  register: async (userData) => {
    return fetchWithCredentials(`${API_BASE_URL}/auth/register.php`, {
      method: 'POST',
      body: JSON.stringify(userData),
    });
  },

  // Logout user
  logout: async () => {
    return fetchWithCredentials(`${API_BASE_URL}/auth/logout.php`, {
      method: 'POST',
    });
  },

  // Check session
  checkSession: async () => {
    return fetchWithCredentials(`${API_BASE_URL}/auth/check-session.php`);
  },
};

// Menu Items API
export const menuAPI = {
  // Get all menu items with optional filters
  getItems: async (filters = {}) => {
    const params = new URLSearchParams();

    if (filters.category) params.append('category', filters.category);
    if (filters.search) params.append('search', filters.search);
    if (filters.sortBy) params.append('sortBy', filters.sortBy);
    if (filters.sortOrder) params.append('sortOrder', filters.sortOrder);

    const queryString = params.toString();
    const url = `${API_BASE_URL}/menu_items.php${queryString ? '?' + queryString : ''}`;

    return fetchWithCredentials(url);
  },

  // Create new menu item (admin only)
  createItem: async (itemData) => {
    return fetchWithCredentials(`${API_BASE_URL}/menu_items.php`, {
      method: 'POST',
      body: JSON.stringify(itemData),
    });
  },

  // Update menu item (admin only)
  updateItem: async (itemData) => {
    return fetchWithCredentials(`${API_BASE_URL}/menu_items.php`, {
      method: 'PUT',
      body: JSON.stringify(itemData),
    });
  },

  // Delete menu item (admin only)
  deleteItem: async (itemId) => {
    return fetchWithCredentials(`${API_BASE_URL}/menu_items.php`, {
      method: 'DELETE',
      body: JSON.stringify({ id: itemId }),
    });
  },
};

// Cart API
export const cartAPI = {
  // Get cart items
  getCart: async () => {
    return fetchWithCredentials(`${API_BASE_URL}/cart.php`);
  },

  // Add item to cart
  addItem: async (menuItemId, quantity = 1) => {
    return fetchWithCredentials(`${API_BASE_URL}/cart.php`, {
      method: 'POST',
      body: JSON.stringify({ menu_item_id: menuItemId, quantity }),
    });
  },

  // Add to cart (alias for addItem)
  addToCart: async (menuItemId, quantity = 1) => {
    return fetchWithCredentials(`${API_BASE_URL}/cart.php`, {
      method: 'POST',
      body: JSON.stringify({ menu_item_id: menuItemId, quantity }),
    });
  },

  // Update cart item quantity
  updateItem: async (cartItemId, quantity) => {
    return fetchWithCredentials(`${API_BASE_URL}/cart.php`, {
      method: 'PUT',
      body: JSON.stringify({ cart_item_id: cartItemId, quantity }),
    });
  },

  // Update quantity (alias for updateItem)
  updateQuantity: async (cartItemId, quantity) => {
    return fetchWithCredentials(`${API_BASE_URL}/cart.php`, {
      method: 'PUT',
      body: JSON.stringify({ cart_item_id: cartItemId, quantity }),
    });
  },

  // Remove item from cart
  removeItem: async (cartItemId) => {
    return fetchWithCredentials(`${API_BASE_URL}/cart.php`, {
      method: 'DELETE',
      body: JSON.stringify({ cart_item_id: cartItemId }),
    });
  },

  // Remove from cart (alias for removeItem)
  removeFromCart: async (cartItemId) => {
    return fetchWithCredentials(`${API_BASE_URL}/cart.php`, {
      method: 'DELETE',
      body: JSON.stringify({ cart_item_id: cartItemId }),
    });
  },
};

// Feedback API
export const feedbackAPI = {
  // Get approved feedbacks
  getFeedbacks: async () => {
    return fetchWithCredentials(`${API_BASE_URL}/feedback.php`);
  },

  // Submit new feedback
  submitFeedback: async (feedbackData) => {
    return fetchWithCredentials(`${API_BASE_URL}/feedback.php`, {
      method: 'POST',
      body: JSON.stringify(feedbackData),
    });
  },

  // Approve feedback (admin only)
  approveFeedback: async (feedbackId) => {
    return fetchWithCredentials(`${API_BASE_URL}/feedback.php`, {
      method: 'PUT',
      body: JSON.stringify({ id: feedbackId }),
    });
  },

  // Delete feedback (admin only)
  deleteFeedback: async (feedbackId) => {
    return fetchWithCredentials(`${API_BASE_URL}/feedback.php`, {
      method: 'DELETE',
      body: JSON.stringify({ id: feedbackId }),
    });
  },
};

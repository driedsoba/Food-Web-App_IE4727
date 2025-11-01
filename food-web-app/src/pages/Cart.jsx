import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { cartAPI, ordersAPI } from '../services/api';
import { useAuth } from '../hooks/useAuth';
import './Cart.css';

const Cart = () => {
  const navigate = useNavigate();
  const { user } = useAuth();
  const [cartItems, setCartItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [placingOrder, setPlacingOrder] = useState(false);
  const [deliveryInfo, setDeliveryInfo] = useState({
    customerName: '',
    customerEmail: '',
    customerPhone: '',
    deliveryAddress: ''
  });
  const [validationErrors, setValidationErrors] = useState({});

  useEffect(() => {
    fetchCart();
    // Pre-fill name and email if user is logged in
    if (user) {
      setDeliveryInfo(prev => ({
        ...prev,
        customerName: user.full_name || user.username || '',
        customerEmail: user.email || ''
      }));
    }
  }, [user]);

  const fetchCart = async () => {
    try {
      setLoading(true);
      const data = await cartAPI.getCart();
      setCartItems(data);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  // Validation functions
  const validateAddress = (address) => {
    if (!address || address.trim().length < 10) {
      return "Address must be at least 10 characters";
    }
    return "";
  };

  const validatePhoneNumber = (phone) => {
    // Remove any whitespace
    const cleanPhone = phone.replace(/\s/g, "");
    
    // Check if exactly 8 digits
    if (!/^\d{8}$/.test(cleanPhone)) {
      return "Phone number must be exactly 8 digits (e.g., 88469676)";
    }
    return "";
  };

  const validateEmail = (email) => {
    if (!email || !email.trim()) {
      return "Email is required";
    }
    // Basic email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      return "Please enter a valid email address";
    }
    return "";
  };

  const validateForm = () => {
    const errors = {};
    
    if (!deliveryInfo.customerName.trim()) {
      errors.customerName = "Name is required";
    }
    
    const emailError = validateEmail(deliveryInfo.customerEmail);
    if (emailError) errors.customerEmail = emailError;
    
    const phoneError = validatePhoneNumber(deliveryInfo.customerPhone);
    if (phoneError) errors.customerPhone = phoneError;
    
    const addressError = validateAddress(deliveryInfo.deliveryAddress);
    if (addressError) errors.deliveryAddress = addressError;
    
    setValidationErrors(errors);
    return Object.keys(errors).length === 0;
  };

  const calculateTotal = () => {
    return cartItems.reduce((total, item) => {
      return total + (parseFloat(item.price) * item.quantity);
    }, 0);
  };

  const getTotalItems = () => {
    return cartItems.reduce((total, item) => total + item.quantity, 0);
  };

  const handleUpdateQuantity = async (cartItemId, newQuantity) => {
    if (newQuantity < 1) return;

    try {
      await cartAPI.updateQuantity(cartItemId, newQuantity);
      fetchCart();
    } catch (error) {
      alert(error.message || 'Failed to update quantity');
    }
  };

  const handleRemoveItem = async (cartItemId) => {
    try {
      await cartAPI.removeFromCart(cartItemId);
      fetchCart();
    } catch (error) {
      alert(error.message || 'Failed to remove item');
    }
  };

  const handlePlaceOrder = async () => {
    if (cartItems.length === 0) {
      alert('Your cart is empty');
      return;
    }

    // Validate delivery information with detailed checks
    if (!validateForm()) {
      return; // Validation errors will be displayed in the form
    }

    setPlacingOrder(true);
    try {
      // Prepare items for order creation
      const orderItems = cartItems.map(item => ({
        menu_item_id: item.menu_item_id,
        quantity: item.quantity,
        price: item.price
      }));

      // Create the order with delivery information
      const result = await ordersAPI.createOrder({
        items: orderItems,
        customer_name: deliveryInfo.customerName,
        customer_email: deliveryInfo.customerEmail,
        customer_phone: deliveryInfo.customerPhone,
        delivery_address: deliveryInfo.deliveryAddress
      });

      if (result.success) {
        // Clear the cart after successful order
        await cartAPI.clearCart();

        // Navigate to order status page
        navigate('/order-status');
      } else {
        throw new Error(result.error || 'Failed to create order');
      }
    } catch (error) {
      console.error('Order creation error:', error);
      alert(error.message || 'Failed to place order. Please try again.');
    } finally {
      setPlacingOrder(false);
    }
  };

  if (loading) {
    return (
      <div className="cart-page">
        <div className="cart-container">
          <p className="loading-message">Loading cart...</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="cart-page">
        <div className="cart-container">
          <p className="error-message">Error: {error}</p>
        </div>
      </div>
    );
  }

  return (
    <div className="cart-page">
      <div className="cart-container">
        <h1>Your Cart</h1>

        {cartItems.length === 0 ? (
          <div className="empty-cart">
            <h2>Your cart is empty</h2>
            <p>Add some delicious items from our menu!</p>
            <button onClick={() => navigate('/menu')} className="continue-shopping">
              Browse Menu
            </button>
          </div>
        ) : (
          <div className="cart-content">
            <div className="cart-items">
              {cartItems.map((item) => (
                <div key={item.id} className="cart-item">
                  <img src={item.image_url} alt={item.name} />
                  <div className="item-details">
                    <h3>{item.name}</h3>
                    <p className="item-category">{item.category}</p>
                    <p className="item-price">${parseFloat(item.price).toFixed(2)}</p>
                  </div>
                  <div className="quantity-controls">
                    <button
                      onClick={() => handleUpdateQuantity(item.id, item.quantity - 1)}
                      className="qty-btn"
                    >
                      −
                    </button>
                    <span className="quantity">{item.quantity}</span>
                    <button
                      onClick={() => handleUpdateQuantity(item.id, item.quantity + 1)}
                      className="qty-btn"
                    >
                      +
                    </button>
                  </div>
                  <p className="item-total">
                    ${(parseFloat(item.price) * item.quantity).toFixed(2)}
                  </p>
                  <button
                    onClick={() => handleRemoveItem(item.id)}
                    className="remove-btn"
                    title="Remove item"
                  >
                    Remove
                  </button>
                </div>
              ))}
            </div>

            <div className="cart-summary">
              <h2>Order Summary</h2>
              <div className="summary-row">
                <span>Subtotal ({getTotalItems()} items):</span>
                <span>${calculateTotal().toFixed(2)}</span>
              </div>
              <div className="summary-row">
                <span>Delivery Fee:</span>
                <span>$5.00</span>
              </div>
              <div className="summary-divider"></div>
              <div className="summary-row total">
                <span>Total:</span>
                <span>${(calculateTotal() + 5.00).toFixed(2)}</span>
              </div>

              <div className="delivery-info-section">
                <h3>Delivery Information</h3>
                <div className="form-group">
                  <label htmlFor="customerName">Full Name *</label>
                  <input
                    type="text"
                    id="customerName"
                    value={deliveryInfo.customerName}
                    onChange={(e) => {
                      setDeliveryInfo({ ...deliveryInfo, customerName: e.target.value });
                      if (validationErrors.customerName) {
                        setValidationErrors({ ...validationErrors, customerName: "" });
                      }
                    }}
                    placeholder="John Doe"
                    className={validationErrors.customerName ? 'error' : ''}
                    required
                  />
                  {validationErrors.customerName && (
                    <span className="error-message">{validationErrors.customerName}</span>
                  )}
                </div>
                <div className="form-group">
                  <label htmlFor="customerEmail">Email *</label>
                  <input
                    type="email"
                    id="customerEmail"
                    value={deliveryInfo.customerEmail}
                    onChange={(e) => {
                      setDeliveryInfo({ ...deliveryInfo, customerEmail: e.target.value });
                      if (validationErrors.customerEmail) {
                        setValidationErrors({ ...validationErrors, customerEmail: "" });
                      }
                    }}
                    placeholder="john@example.com"
                    className={validationErrors.customerEmail ? 'error' : ''}
                    required
                  />
                  {validationErrors.customerEmail && (
                    <span className="error-message">{validationErrors.customerEmail}</span>
                  )}
                </div>
                <div className="form-group">
                  <label htmlFor="customerPhone">Phone Number *</label>
                  <input
                    type="tel"
                    id="customerPhone"
                    value={deliveryInfo.customerPhone}
                    onChange={(e) => {
                      setDeliveryInfo({ ...deliveryInfo, customerPhone: e.target.value });
                      if (validationErrors.customerPhone) {
                        setValidationErrors({ ...validationErrors, customerPhone: "" });
                      }
                    }}
                    placeholder="88469676"
                    maxLength={8}
                    className={validationErrors.customerPhone ? 'error' : ''}
                    required
                  />
                  {validationErrors.customerPhone && (
                    <span className="error-message">{validationErrors.customerPhone}</span>
                  )}
                  <small className="input-hint">Must be exactly 8 digits</small>
                </div>
                <div className="form-group">
                  <label htmlFor="deliveryAddress">Delivery Address *</label>
                  <textarea
                    id="deliveryAddress"
                    value={deliveryInfo.deliveryAddress}
                    onChange={(e) => {
                      setDeliveryInfo({ ...deliveryInfo, deliveryAddress: e.target.value });
                      if (validationErrors.deliveryAddress) {
                        setValidationErrors({ ...validationErrors, deliveryAddress: "" });
                      }
                    }}
                    placeholder="123 Main St, #01-23, Singapore 123456"
                    rows="3"
                    className={validationErrors.deliveryAddress ? 'error' : ''}
                    required
                  />
                  {validationErrors.deliveryAddress && (
                    <span className="error-message">{validationErrors.deliveryAddress}</span>
                  )}
                  <small className="input-hint">Minimum 10 characters</small>
                </div>
              </div>

              <button
                className="checkout-btn"
                onClick={handlePlaceOrder}
                disabled={placingOrder}
              >
                {placingOrder ? 'Placing Order...' : 'Place Order'}
              </button>
              <button
                className="continue-shopping-btn"
                onClick={() => navigate('/menu')}
              >
                Continue Shopping
              </button>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default Cart;

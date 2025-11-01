import { useState, useEffect } from 'react';
import { useAuth } from '../hooks/useAuth';
import { feedbackAPI, ordersAPI } from '../services/api';
import './Feedback.css';

const Feedback = () => {
  const { user, isAuthenticated } = useAuth();
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    rating: 0,
    orderNumber: '',
    feedback: ''
  });
  const [feedbacks, setFeedbacks] = useState([]);
  const [userOrders, setUserOrders] = useState([]);
  const [hoveredRating, setHoveredRating] = useState(0);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  // Pre-fill name and email if user is logged in
  useEffect(() => {
    if (isAuthenticated && user) {
      setFormData(prev => ({
        ...prev,
        name: user.full_name || user.username || '',
        email: user.email || ''
      }));
      // Fetch user's orders for the dropdown
      fetchUserOrders();
    }
  }, [isAuthenticated, user]);

  // Fetch user's orders
  const fetchUserOrders = async () => {
    try {
      const response = await ordersAPI.getOrders();
      setUserOrders(response.orders || []);
    } catch (err) {
      console.error('Error fetching user orders:', err);
    }
  };

  // Fetch feedbacks on component mount
  useEffect(() => {
    fetchFeedbacks();
  }, []);

  const fetchFeedbacks = async () => {
    try {
      const data = await feedbackAPI.getFeedbacks();
      setFeedbacks(data);
    } catch (err) {
      console.error('Error fetching feedbacks:', err);
    }
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value
    });
    setError('');
  };

  const handleRatingClick = (rating) => {
    setFormData({
      ...formData,
      rating
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      const result = await feedbackAPI.submitFeedback(formData);

      if (result.success) {
        // Reset form
        setFormData({
          name: isAuthenticated && user ? (user.full_name || user.username || '') : '',
          email: isAuthenticated && user ? (user.email || '') : '',
          rating: 0,
          orderNumber: '',
          feedback: ''
        });

        alert('Thank you for your feedback! It will be reviewed before being published.');

        // Refresh feedbacks
        fetchFeedbacks();
      }
    } catch (err) {
      setError(err.message || 'Failed to submit feedback. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  const renderStars = (rating, isInteractive = false) => {
    return [...Array(5)].map((_, index) => {
      const starRating = index + 1;
      const isFilled = isInteractive
        ? starRating <= (hoveredRating || formData.rating)
        : starRating <= rating;

      return (
        <span
          key={index}
          className={`star ${isFilled ? 'filled' : ''} ${isInteractive ? 'interactive' : ''}`}
          onClick={isInteractive ? () => handleRatingClick(starRating) : undefined}
          onMouseEnter={isInteractive ? () => setHoveredRating(starRating) : undefined}
          onMouseLeave={isInteractive ? () => setHoveredRating(0) : undefined}
        >
          ★
        </span>
      );
    });
  };

  return (
    <div className="feedback-page">
      <section className="feedback-form-section">
        <div className="feedback-icon">💬</div>
        <h1>We Value Your Feedback</h1>
        <p className="feedback-subtitle">
          Your opinion helps us improve our service. Share your experience with Lecker Haus and help us serve you better.
        </p>

        {error && (
          <div className="error-message" style={{
            backgroundColor: '#fee',
            color: '#c33',
            padding: '12px',
            borderRadius: '8px',
            marginBottom: '20px',
            maxWidth: '700px',
            margin: '0 auto 20px'
          }}>
            {error}
          </div>
        )}

        <div className="feedback-form-container">
          <h2>Share Your Experience</h2>
          <form onSubmit={handleSubmit} className="feedback-form">
            <div className="form-group">
              <label htmlFor="name">Your Name *</label>
              <input
                type="text"
                id="name"
                name="name"
                value={formData.name}
                onChange={handleInputChange}
                placeholder="John Doe"
                required
                disabled={loading}
              />
            </div>

            <div className="form-group">
              <label htmlFor="email">Email Address *</label>
              <input
                type="email"
                id="email"
                name="email"
                value={formData.email}
                onChange={handleInputChange}
                placeholder="john@example.com"
                required
                disabled={loading}
              />
            </div>

            <div className="form-group">
              <label>Rate Your Experience *</label>
              <div className="rating-stars">
                {renderStars(formData.rating, true)}
              </div>
            </div>

            <div className="form-group">
              <label htmlFor="orderNumber">Order Number (Optional)</label>
              {isAuthenticated && userOrders.length > 0 ? (
                <select
                  id="orderNumber"
                  name="orderNumber"
                  value={formData.orderNumber}
                  onChange={handleInputChange}
                  disabled={loading}
                  className="order-select"
                >
                  <option value="">Select an order (optional)</option>
                  {userOrders.map((order) => (
                    <option key={order.id} value={order.id}>
                      Order #{order.id} - ${Number(order.total_amount).toFixed(2)} ({new Date(order.created_at).toLocaleDateString()})
                    </option>
                  ))}
                </select>
              ) : (
                <input
                  type="text"
                  id="orderNumber"
                  name="orderNumber"
                  value={formData.orderNumber}
                  onChange={handleInputChange}
                  placeholder="#12345"
                  disabled={loading}
                />
              )}
            </div>

            <div className="form-group">
              <label htmlFor="feedback">Your Feedback *</label>
              <textarea
                id="feedback"
                name="feedback"
                value={formData.feedback}
                onChange={handleInputChange}
                placeholder="Tell us about your experience with our service, food quality, delivery time, etc..."
                rows="5"
                required
                disabled={loading}
              />
            </div>

            <button type="submit" className="submit-button" disabled={loading}>
              {loading ? 'Submitting...' : 'Submit Feedback'}
            </button>
          </form>
        </div>
      </section>

      <section className="feedback-display-section">
        <h2>What Our Customers Say</h2>
        {feedbacks.length > 0 ? (
          <div className="feedbacks-list">
            {feedbacks.map((feedback) => (
              <div key={feedback.id} className="feedback-card">
                <div className="feedback-header">
                  <div className="feedback-author">
                    <h3>{feedback.name}</h3>
                    <div className="feedback-rating">
                      {renderStars(feedback.rating)}
                    </div>
                  </div>
                  <span className="feedback-date">
                    {new Date(feedback.created_at).toLocaleDateString()}
                  </span>
                </div>
                <p className="feedback-text">{feedback.feedback}</p>
              </div>
            ))}
          </div>
        ) : (
          <p style={{ textAlign: 'center', color: '#666', padding: '40px' }}>
            No feedbacks yet. Be the first to share your experience!
          </p>
        )}
      </section>
    </div>
  );
};

export default Feedback;

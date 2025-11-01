import { useNavigate, useLocation } from 'react-router-dom';
import './OrderConfirmation.css';

const OrderConfirmation = () => {
  const navigate = useNavigate();
  const location = useLocation();
  const orderId = location.state?.orderId;

  return (
    <div className="confirmation-page">
      <div className="confirmation-container">
        <div className="success-icon">
          <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
            <circle cx="40" cy="40" r="40" fill="#4CAF50" />
            <path
              d="M25 40L35 50L55 30"
              stroke="white"
              strokeWidth="5"
              strokeLinecap="round"
              strokeLinejoin="round"
            />
          </svg>
        </div>

        <h1>Order Confirmed!</h1>
        <p className="success-message">
          Thank you for your order. Your delicious German meal is on its way!
        </p>

        <div className="order-info-box">
          <h2>Order Details</h2>
          <div className="order-number">
            <strong>Order Number:</strong>{' '}
            <span className="highlight">#{orderId || '...'}</span>
          </div>
          <p className="estimated-time">
            <strong>Estimated Delivery:</strong> 45-60 minutes
          </p>
        </div>

        <div className="next-steps">
          <h3>What's Next?</h3>
          <ul>
            <li>✓ We've received your order and started preparing it</li>
            <li>✓ You'll receive a confirmation email shortly</li>
            <li>✓ Track your order in your order history</li>
            <li>✓ Our delivery team will contact you if needed</li>
          </ul>
        </div>

        <div className="action-buttons">
          <button
            onClick={() => navigate('/order-status')}
            className="btn-primary"
          >
            View Order Status
          </button>
          <button
            onClick={() => navigate('/menu')}
            className="btn-secondary"
          >
            Order More
          </button>
          <button
            onClick={() => navigate('/')}
            className="btn-secondary"
          >
            Back to Home
          </button>
        </div>

        <div className="support-info">
          <p>Need help with your order?</p>
          <p>
            <strong>Contact us:</strong> +65 6748 9380 | support@leckerhaus.com
          </p>
        </div>
      </div>
    </div>
  );
};

export default OrderConfirmation;

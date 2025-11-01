import { useState } from 'react';
import './Catering.css';

const Catering = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    eventDate: '',
    guestCount: '',
    package: '',
    message: ''
  });
  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState('');
  const [error, setError] = useState('');

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value
    });
    setError('');
    setSuccess('');
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setSuccess('');

    try {
      const response = await fetch('http://localhost/Food-Web-App_IE4727/food-web-app/backend/api/catering.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
      });

      const data = await response.json();

      if (response.ok) {
        setSuccess(data.message);
        // Reset form
        setFormData({
          name: '',
          email: '',
          phone: '',
          eventDate: '',
          guestCount: '',
          package: '',
          message: ''
        });
      } else {
        setError(data.error || 'Failed to send inquiry. Please try again.');
      }
    } catch (err) {
      setError('Failed to send inquiry. Please check your connection and try again.');
      console.error('Error:', err);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="catering-page">
      <section className="catering-form-section">
        <div className="catering-container">
          <div className="menu-header">
            <h1>Compare Packages</h1>
            <p>Compare our catering packages to find the perfect fit for your event</p>
          </div>

          <div className="table-wrapper">
            <table className="catering-table" role="table">
              <caption className="sr-only">Comparison of Essential, Premium and Deluxe catering packages</caption>
              <thead>
                <tr>
                  <th scope="col">Feature</th>
                  <th scope="col">Essential</th>
                  <th scope="col" className="popular-column">Premium</th>
                  <th scope="col">Deluxe</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row" className="feature-name">Price per Person</th>
                  <td>$15–20</td>
                  <td className="popular-column">$25–35</td>
                  <td>$40–50</td>
                </tr>
                <tr>
                  <th scope="row" className="feature-name">Main Course Options</th>
                  <td>3 options</td>
                  <td className="popular-column">5 options</td>
                  <td>Unlimited</td>
                </tr>
                <tr>
                  <th scope="row" className="feature-name">Side Dishes</th>
                  <td>2 sides</td>
                  <td className="popular-column">4 sides</td>
                  <td>Full selection</td>
                </tr>
                <tr>
                  <th scope="row" className="feature-name">Appetizers</th>
                  <td>—</td>
                  <td className="popular-column">Selection</td>
                  <td>Premium selection</td>
                </tr>
                <tr>
                  <th scope="row" className="feature-name">Desserts</th>
                  <td>—</td>
                  <td className="popular-column">Dessert bar</td>
                  <td>Dessert & coffee bar</td>
                </tr>
                <tr>
                  <th scope="row" className="feature-name">Setup</th>
                  <td>Basic</td>
                  <td className="popular-column">Premium</td>
                  <td>Full service</td>
                </tr>
                <tr>
                  <th scope="row" className="feature-name">Tableware</th>
                  <td>Disposable</td>
                  <td className="popular-column">Basic glassware</td>
                  <td>Premium Glassware</td>
                </tr>
                <tr>
                  <th scope="row" className="feature-name">Service Staff</th>
                  <td>—</td>
                  <td className="popular-column">—</td>
                  <td aria-label="Included">✓</td>
                </tr>
                <tr>
                  <th scope="row" className="feature-name">Custom Menu</th>
                  <td>—</td>
                  <td className="popular-column">—</td>
                  <td aria-label="Included">✓</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <section className="catering-contact">
        <div className="catering-container">
          <h2>Request a Catering Quote</h2>
          <p style={{ fontSize: '1.1rem', marginBottom: '2rem' }}>
            Fill out the form below and we'll get back to you within 24 hours
          </p>

          {success && (
            <div className="success-message" style={{
              backgroundColor: '#d4edda',
              color: '#155724',
              padding: '1rem',
              borderRadius: '8px',
              marginBottom: '1.5rem',
              border: '1px solid #c3e6cb'
            }}>
              {success}
            </div>
          )}

          {error && (
            <div className="error-message" style={{
              backgroundColor: '#f8d7da',
              color: '#721c24',
              padding: '1rem',
              borderRadius: '8px',
              marginBottom: '1.5rem',
              border: '1px solid #f5c6cb'
            }}>
              {error}
            </div>
          )}

          <form onSubmit={handleSubmit} className="catering-form">
            <div className="form-row">
              <div className="form-group">
                <label htmlFor="name">Your Name *</label>
                <input
                  type="text"
                  id="name"
                  name="name"
                  value={formData.name}
                  onChange={handleInputChange}
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
                  required
                  disabled={loading}
                />
              </div>
            </div>

            <div className="form-row">
              <div className="form-group">
                <label htmlFor="phone">Phone Number *</label>
                <input
                  type="tel"
                  id="phone"
                  name="phone"
                  value={formData.phone}
                  onChange={handleInputChange}
                  placeholder="67489380"
                  required
                  disabled={loading}
                />
              </div>

              <div className="form-group">
                <label htmlFor="eventDate">Event Date *</label>
                <input
                  type="date"
                  id="eventDate"
                  name="eventDate"
                  value={formData.eventDate}
                  onChange={handleInputChange}
                  required
                  disabled={loading}
                />
              </div>
            </div>

            <div className="form-row">
              <div className="form-group">
                <label htmlFor="guestCount">Number of Guests *</label>
                <input
                  type="number"
                  id="guestCount"
                  name="guestCount"
                  value={formData.guestCount}
                  onChange={handleInputChange}
                  min="1"
                  required
                  disabled={loading}
                />
              </div>

              <div className="form-group">
                <label htmlFor="package">Package *</label>
                <select
                  id="package"
                  name="package"
                  value={formData.package}
                  onChange={handleInputChange}
                  required
                  disabled={loading}
                >
                  <option value="">Select a package</option>
                  <option value="Essential">Essential ($15-20 per person)</option>
                  <option value="Premium">Premium ($25-35 per person)</option>
                  <option value="Deluxe">Deluxe ($40-50 per person)</option>
                </select>
              </div>
            </div>

            <div className="form-group">
              <label htmlFor="message">Additional Information (Optional)</label>
              <textarea
                id="message"
                name="message"
                value={formData.message}
                onChange={handleInputChange}
                rows="4"
                placeholder="Tell us more about your event, dietary restrictions, special requests, etc."
                disabled={loading}
              />
            </div>

            <button type="submit" className="submit-btn" disabled={loading}>
              {loading ? 'Sending...' : 'Send Inquiry'}
            </button>
          </form>

          <div className="contact-info" style={{ marginTop: '2rem', textAlign: 'center' }}>
            <p style={{ fontSize: '1rem', color: '#666' }}>
              Or contact us directly at:
            </p>
            <p style={{ fontSize: '1.3rem', fontWeight: 'bold', color: '#ff6b1a', marginTop: '0.5rem' }}>
              f32ee@localhost | +65 6748 9380
            </p>
          </div>
        </div>
      </section>
    </div>
  )
}

export default Catering

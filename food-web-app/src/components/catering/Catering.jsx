import './Catering.css'

const Catering = () => {
  return (
    <div className="catering-page">
      {/* Hero removed per request; page starts with package comparison table */}

      {/* Package Comparison Table Section */}
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
                  <td className="popular-column">China & glassware</td>
                  <td>Premium tableware</td>
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

      {/* Features Section */}
      <section className="catering-features">
        <div className="catering-container">
          <h2>Why Choose Our Catering</h2>
          <div className="features-grid">
            <div className="feature-item">
              <span className="feature-icon-large">👨‍🍳</span>
              <h3>Expert Chefs</h3>
              <p>Authentic German recipes prepared by experienced culinary professionals</p>
            </div>
            <div className="feature-item">
              <span className="feature-icon-large">🍽️</span>
              <h3>Flexible Options</h3>
              <p>Customizable menus to suit dietary restrictions and preferences</p>
            </div>
            <div className="feature-item">
              <span className="feature-icon-large">🚚</span>
              <h3>Full Service</h3>
              <p>Delivery, setup, service staff, and cleanup all included</p>
            </div>
            <div className="feature-item">
              <span className="feature-icon-large">⭐</span>
              <h3>Quality Guaranteed</h3>
              <p>Fresh ingredients and impeccable presentation every time</p>
            </div>
          </div>
        </div>
      </section>

      {/* Contact Info Section */}
      <section className="catering-contact">
        <div className="catering-container">
          <h2>Questions?</h2>
          <p>Contact our catering team directly</p>
          <div className="contact-details">
            <div className="contact-item">
              <a href="mailto:catering@leckerhaus.com">catering@leckerhaus.com</a>
            </div>
          </div>
        </div>
      </section>
    </div>
  )
}

export default Catering

import React from 'react';
import './Footer.css';

const Footer = () => {
  return (
    <footer className="footer">
      <div className="footer-container">
        <div className="footer-content">
          <div className="footer-section">
            <h3>LeckerHaus</h3>
            <p>Delicious food delivered to your doorstep</p>
          </div>
          <div className="footer-section">
            <h4>Quick Links</h4>
            <ul>
              <li><a href="/">Home</a></li>
              <li><a href="/menu">Menu</a></li>
              <li><a href="/catering">Catering</a></li>
              <li><a href="/feedback">Feedback</a></li>
            </ul>
          </div>
          <div className="footer-section">
            <h4>Contact Info</h4>
            <p>General Enquiries: info@leckerhaus.com</p>
            <p>Phone: +65 6748 9380</p>
            <p>Address: 50 Nanyang Ave, Singapore 639798</p>
          </div>
        </div>
        <div className="footer-bottom">
          <p>&copy; 2025 Lecker Haus. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
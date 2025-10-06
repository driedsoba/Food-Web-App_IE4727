import React from 'react';
import './Footer.css';

const Footer = () => {
  return (
    <footer className="footer">
      <div className="footer-container">
        <div className="footer-content">
          <div className="footer-section">
            <h3>Food Web App</h3>
            <p>Delicious food delivered to your doorstep</p>
          </div>
          <div className="footer-section">
            <h4>Quick Links</h4>
            <ul>
              <li><a href="#home">Home</a></li>
              <li><a href="#menu">Menu</a></li>
              <li><a href="#about">About Us</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div>
          <div className="footer-section">
            <h4>Contact Info</h4>
            <p>📧 info@foodwebapp.com</p>
            <p>📞 +1 (555) 123-4567</p>
            <p>📍 123 Food Street, City, State</p>
          </div>
        </div>
        <div className="footer-bottom">
          <p>&copy; 2025 Food Web App. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
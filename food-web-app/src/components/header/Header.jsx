import React from 'react';
import './Header.css';

const Header = () => {
  return (
    <header className="header">
      <div className="header-container">
        <div className="logo">
          <h1>LeckerHaus</h1>
        </div>
        <nav className="navigation">
          <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#menu">Menu</a></li>
            <li><a href="#catering">Catering</a></li>
            <li><a href="#feedback">Feedback</a></li>
          </ul>
        </nav>
        <div className="cart-section">
          <button className="cart-button">
            <span className="cart-icon">🛒</span>
            <span className="cart-text">Cart</span>
          </button>
        </div>
      </div>
    </header>
  );
};

export default Header;
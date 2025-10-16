import React from 'react';
import { Link } from 'react-router-dom';
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
            <li><Link to="/">Home</Link></li>
            <li><Link to="/menu">Menu</Link></li>
            <li><Link to="/catering">Catering</Link></li>
            <li><Link to="/feedback">Feedback</Link></li>
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
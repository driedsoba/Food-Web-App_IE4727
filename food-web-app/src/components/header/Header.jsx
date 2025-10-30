import React from 'react';
import { Link, NavLink } from 'react-router-dom';
import { useAuth } from '../../hooks/useAuth';
import './Header.css';

const Header = () => {
  const { user, isAuthenticated, logout } = useAuth();

  const handleLogout = async () => {
    const result = await logout();
    if (result.success) {
      alert('Logged out successfully');
    }
  };

  return (
    <header className="header">
      <div className="header-container">
        <div className="logo">
          <h1>LeckerHaus</h1>
        </div>
        <nav className="navigation">
          <ul>
            <li><NavLink to="/" end>Home</NavLink></li>
            <li><NavLink to="/menu">Menu</NavLink></li>
            <li><NavLink to="/catering">Catering</NavLink></li>
            <li>
              <a href="http://localhost/Food-Web-App_IE4727/food-web-app/backend/order-history.php">
                Order History
              </a>
            </li>
            <li><NavLink to="/feedback">Feedback</NavLink></li>
          </ul>
        </nav>
        <div className="cart-section">
          {isAuthenticated ? (
            <>
              <span className="user-greeting">Hello, {user?.username}!</span>
              <button className="logout-button" onClick={handleLogout}>
                Logout
              </button>
            </>
          ) : (
            <Link to="/login" className="login-button">
              <span className="login-text">Login</span>
            </Link>
          )}
          <Link to="/cart" className="cart-button">
            <span className="cart-text">Cart</span>
          </Link>
        </div>
      </div>
    </header>
  );
};

export default Header;
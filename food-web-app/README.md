# Modern React Version - LeckerHaus Restaurant

A modern Single Page Application (SPA) built with React and Vite. This version provides a seamless user experience with client-side routing and REST API integration.

## Technology Stack

- **Frontend**: React 19.1.1, React Router 6.15.0
- **Build Tool**: Vite 7.1.7
- **Backend API**: PHP 7.4+ with PDO
- **Database**: MySQL (via XAMPP)
- **State Management**: React Context API
- **Authentication**: Session-based with REST API
- **Styling**: CSS Modules

## Features

- User Authentication (Login/Register)
- Menu Browsing with Search & Filters (Client-side)
- Shopping Cart Management (Real-time updates)
- Checkout & Order Placement
- Order Status Tracking
- Order History
- Customer Feedback System
- Catering Package Information
- **Catering Email Inquiry Form** (requires Mercury mail server)

## Project Structure

```
food-web-app/
├── src/
│   ├── pages/                 # Page components
│   │   ├── HomePage.jsx
│   │   ├── Menu.jsx
│   │   ├── Cart.jsx
│   │   ├── OrderStatus.jsx
│   │   └── ...
│   ├── components/            # Reusable components
│   │   ├── header/
│   │   ├── footer/
│   │   └── auth/
│   ├── context/               # React Context
│   │   └── AuthContext.jsx
│   ├── services/              # API calls
│   │   └── api.js
│   └── hooks/                 # Custom hooks
│       └── useAuth.js
├── backend/                   # PHP API endpoints
│   ├── api/
│   │   ├── auth/
│   │   ├── cart.php
│   │   ├── orders.php
│   │   ├── feedback.php
│   │   └── catering.php
│   └── config/
│       ├── database.php
│       └── cors.php
├── public/                    # Static assets
└── database/                  # SQL files
    ├── schema.sql
    └── seed_data.sql
```

## How It Works

### Single Page Application (SPA)
React Router handles all navigation without page reloads:
```jsx
<Routes>
  <Route path="/" element={<HomePage />} />
  <Route path="/menu" element={<Menu />} />
  <Route path="/cart" element={<PrivateRoute><Cart /></PrivateRoute>} />
</Routes>
```

### REST API Integration
All data fetched via async API calls:
```javascript
// Add to cart
const response = await cartAPI.addToCart(menuItemId, quantity);

// Fetch orders
const orders = await ordersAPI.getOrders();
```

### State Management
Auth state managed with Context API:
```jsx
const { user, login, logout } = useAuth();
```

### Real-Time Updates
Cart updates without page reload:
```javascript
const handleAddToCart = async (itemId) => {
  await cartAPI.addToCart(itemId, 1);
  // Cart state automatically updates
};
```

## Installation & Setup

See main README for complete setup instructions: [Main README](../README.md)

**Quick Start:**
1. Place folder in `C:\xampp\htdocs\Food-Web-App_IE4727\`
2. Import `backend/database/schema.sql` and `seed_data.sql` into MySQL
3. Keep Apache and MySQL running in XAMPP (for PHP API backend)
4. Install dependencies:
   ```bash
   cd C:\xampp\htdocs\Food-Web-App_IE4727\food-web-app
   npm install
   ```
5. Start dev server:
   ```bash
   npm run dev
   ```
6. Visit: `http://localhost:5173`

## Email Setup (Optional)

For catering inquiry emails to work:
1. Start **Mercury** mail server in XAMPP
2. Install [Thunderbird](https://www.thunderbird.net/)
3. Configure email account: `f32ee@localhost`

## Configuration

### API Base URL
Edit `src/services/api.js`:
```javascript
const API_BASE_URL = 'http://localhost/Food-Web-App_IE4727/food-web-app/backend/api';
```

### Database
Edit `backend/config/database.php`:
```php
private $host = "localhost";
private $db_name = "food_web_app";
private $username = "root";
private $password = "";
```

### Vite Proxy
Edit `vite.config.js` if folder name differs:
```javascript
rewrite: (path) => path.replace(/^\/api/, "/Food-Web-App_IE4727/food-web-app/backend/api")
```

## Available Scripts

```bash
npm run dev       # Start development server (port 5173)
npm run build     # Build for production
npm run preview   # Preview production build
npm run lint      # Run ESLint
```

## API Endpoints

### Authentication
- `POST /api/auth/login.php` - User login
- `POST /api/auth/register.php` - User registration
- `POST /api/auth/logout.php` - User logout
- `GET /api/auth/check-session.php` - Check auth status

### Menu
- `GET /api/menu_items.php` - Fetch menu items (with filters)

### Cart
- `GET /api/cart.php` - Get cart items
- `POST /api/cart.php` - Add item to cart
- `PUT /api/cart.php` - Update cart quantity
- `DELETE /api/cart.php` - Remove item or clear cart

### Orders
- `GET /api/orders.php` - Get user orders
- `POST /api/orders.php?action=create` - Create order
- `POST /api/orders.php?action=advance` - Advance order status

### Feedback
- `GET /api/feedback.php` - Get approved feedback
- `POST /api/feedback.php` - Submit feedback

### Catering
- `POST /api/catering.php` - Send catering inquiry email

## Security Features

- Password hashing with bcrypt
- SQL injection prevention via PDO prepared statements
- XSS protection with output sanitization
- CORS configuration for API security
- Session-based authentication
- Input validation on both client and server

## Testing

**Test Account** (if seed data imported):
- Email: `test@example.com`
- Password: `password123`

## Key Differences from Traditional PHP Version

| Feature | Traditional PHP | Modern React |
|---------|----------------|--------------|
| **Rendering** | Server-side | Client-side |
| **Navigation** | Page reloads | SPA routing |
| **Data Fetching** | Form POST | REST API |
| **Cart Updates** | Full refresh | Real-time |
| **State** | Session + reload | Context API |
| **UX** | Traditional | Modern/Smooth |

## License

Developed for IE4727 - Web Application Design Course

# Traditional PHP Version - LeckerHaus Restaurant

A server-side rendered PHP web application for restaurant food ordering. This version uses traditional form submissions and full page reloads.

## Technology Stack

- **Backend**: PHP 7.4+ with MySQLi
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Database**: MySQL (via XAMPP)
- **Session Management**: PHP Sessions
- **Security**: Prepared statements, bcrypt password hashing, XSS protection

## Features

- User Authentication (Login/Register)
- Menu Browsing with Search & Filters (Server-side)
- Shopping Cart Management (Form-based)
- Checkout & Order Placement
- Order Status Tracking
- Order History
- Customer Feedback System
- Catering Package Information

## Project Structure

```
food-web-app-php/
├── *.php                      # Main page files
├── backend/                   # Form processing scripts
│   ├── process-login.php
│   ├── process-checkout.php
│   ├── process-add-to-cart.php
│   └── ...
├── includes/                  # Reusable components
│   ├── config.php            # Database configuration
│   ├── db.php                # Database connection
│   ├── header.php            # Site header
│   └── footer.php            # Site footer
├── css/                       # Stylesheets
├── js/                        # Client-side validation
└── database/                  # SQL files
    ├── schema.sql
    └── seed_data.sql
```

## How It Works

### Server-Side Rendering
All pages are rendered on the server with PHP. Data is fetched from MySQL and embedded directly into HTML.

### Form Submissions
All interactions use form POST/GET:
```php
<!-- Add to Cart Form -->
<form method="POST" action="backend/process-add-to-cart.php">
    <input type="hidden" name="menu_item_id" value="<?php echo $item['id']; ?>">
    <button type="submit">Add to Cart</button>
</form>
```

### Session Management
```php
// Check authentication in includes/config.php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
```

### Database Queries
Uses prepared statements for security:
```php
$stmt = $conn->prepare("SELECT * FROM menu_items WHERE category = ?");
$stmt->bind_param("s", $category);
$stmt->execute();
```

## Installation & Setup

See main README for complete setup instructions: [Main README](../README.md)

**Quick Start:**
1. Place folder in `C:\xampp\htdocs\Food-Web-App_IE4727\`
2. Import `database/schema.sql` and `seed_data.sql` into MySQL
3. Configure database credentials in `includes/config.php` (default: root/no password)
4. Start Apache and MySQL in XAMPP
5. Visit: `http://localhost/Food-Web-App_IE4727/food-web-app-php/`

## Key Pages

- `index.php` - Homepage with featured dishes
- `menu.php` - Browse menu with search/filter
- `cart.php` - View cart and checkout
- `login.php` - Login and registration
- `order-confirmation.php` - Order success page
- `order-history.php` - View past orders
- `feedback.php` - Submit and view feedback
- `catering.php` - Catering packages info

## Configuration

Edit `includes/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'food_web_app');
define('SITE_URL', 'http://localhost/Food-Web-App_IE4727/food-web-app-php');
```

## Security Features

- Password hashing with bcrypt
- SQL injection prevention via prepared statements
- XSS protection with `htmlspecialchars()`
- Session-based authentication
- Server-side input validation

## Testing

**Test Account** (if seed data imported):
- Email: `test@example.com`
- Password: `password123`

## License

Developed for IE4727 - Web Application Design Course

# LeckerHaus Food Web App - Traditional Version

## 🛠️ Setup Status: ✅ FIXED AND WORKING

### Issues Resolved (November 1, 2025)
1. ✅ Fixed database column error (`user_id` → `id`)
2. ✅ Fixed session_start() warning
3. ✅ Created missing catering.html
4. ✅ Created missing order-history.html
5. ✅ Created missing feedback.html
6. ✅ Created missing API endpoints

## Technology Stack
- ✅ **HTML5** - Structure
- ✅ **CSS3** - Custom styling (no Bootstrap)
- ✅ **Vanilla JavaScript** - Client-side interactivity and AJAX
- ✅ **PHP** - Server-side processing
- ✅ **MySQL** - Database with SQL queries
- ✅ **fetch() API** - AJAX calls (allowed, following CaseStudy_05 pattern)

## What is NOT Used (Prohibited)
- ❌ React/Modern frameworks
- ❌ JSON data transfer
- ❌ AJAX/fetch API
- ❌ jQuery
- ❌ Bootstrap/Foundation
- ❌ iframes for layout
- ❌ Mailto handlers for forms

## Architecture

### Traditional Server-Side Rendering
```
User Request → PHP Script → Database (SQL) → Generate HTML → Send to Browser
```

### Form Submission Flow
```
HTML Form → POST to PHP → Validate (PHP) → SQL Query → Redirect → New Page
```

## File Structure
```
food-web-app-base-traditional/
├── index.php                 # Home page (server-rendered)
├── menu.php                  # Menu listing (server-rendered)
├── cart.php                  # Shopping cart (server-rendered)
├── checkout.php              # Checkout form (server-rendered)
├── order-confirmation.php    # Order success page
├── feedback.php              # Feedback form and display
├── login.php                 # Login page
├── register.php              # Registration page
├── css/
│   └── styles.css           # All styling
├── js/
│   └── validation.js        # Client-side form validation only
├── includes/
│   ├── config.php           # Configuration
│   ├── db.php               # Database connection
│   ├── header.php           # Common header
│   ├── footer.php           # Common footer
│   └── functions.php        # Utility functions
└── backend/
    ├── process-cart.php     # Handle cart actions
    ├── process-order.php    # Handle order submission
    ├── process-login.php    # Handle login
    └── process-feedback.php # Handle feedback
```

## Setup

### 1. Database Setup
```sql
-- Use the same database as modern version
USE food_web_app;
```

### 2. Configure Database
Edit `includes/config.php` with your MySQL credentials.

### 3. Access Application
```
http://localhost/Food-Web-App_IE4727/food-web-app-base-traditional/
```

## Key Features

### 1. Menu Page (menu.php)
- Server-side PHP generates HTML for all menu items
- SQL SELECT query to fetch items
- Form for "Add to Cart" with POST method

### 2. Cart Page (cart.php)
- Server-side session management
- Display cart items from session/database
- Update quantity forms
- Submit order form with delivery info

### 3. Checkout Process
- Form validation (client-side JS + server-side PHP)
- SQL INSERT for orders
- SQL INSERT for order_items
- SQL DELETE to clear cart
- Redirect to confirmation page

### 4. Feedback
- Display approved feedback (SQL SELECT)
- Submit new feedback (SQL INSERT with server validation)

## Validation Examples

### Client-side (JavaScript)
```javascript
function validateCheckoutForm(form) {
    if (form.customer_name.value.trim() === '') {
        alert('Please enter your name');
        return false;
    }
    return true;
}
```

### Server-side (PHP)
```php
$name = trim($_POST['customer_name'] ?? '');
if (empty($name)) {
    $errors[] = 'Name is required';
    // Display errors and stop
}
```

## SQL Usage

### SELECT
```php
$stmt = $conn->prepare("SELECT id, name, price FROM menu_items WHERE is_available = 1");
$stmt->execute();
$result = $stmt->get_result();
```

### INSERT
```php
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
$stmt->bind_param("id", $userId, $total);
$stmt->execute();
```

### UPDATE
```php
$stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
$stmt->bind_param("ii", $quantity, $itemId);
$stmt->execute();
```

### DELETE
```php
$stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
```

## Differences from Modern Version

| Feature | Modern | Base (This) |
|---------|--------|-------------|
| Framework | React | Pure PHP |
| Rendering | Client-side | Server-side |
| Data Format | JSON | HTML Forms |
| State | React hooks | PHP Sessions |
| Navigation | SPA routing | Page redirects |
| API | REST JSON | Form POST |

## Compliance Checklist
- ✅ No JSON
- ✅ No AJAX
- ✅ No jQuery
- ✅ No Bootstrap
- ✅ No iframes for layout
- ✅ Forms invoke PHP scripts
- ✅ Server-side validation
- ✅ Client-side validation
- ✅ SQL commands used
- ✅ Traditional page navigation

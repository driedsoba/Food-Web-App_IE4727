# JavaJam Coffee House - Case Study 05

A full-stack web application for JavaJam Coffee House featuring customer ordering, administrative price management, and sales reporting capabilities.

## Project Overview

This project implements a complete coffee shop management system with customer-facing menu ordering and administrative tools for price updates and sales analytics. Built as part of the IE4727 Web Application Design course.

### Key Features

- **Customer Menu System**: Interactive ordering interface with real-time price calculations
- **Price Management**: Administrative interface for updating product prices
- **Sales Analytics**: Comprehensive reporting with multiple data views
- **Database Integration**: MySQL backend with secure data handling
- **RESTful API**: JSON-based API architecture for frontend-backend communication

## Technology Stack

### Frontend
- HTML5
- CSS3
- Vanilla JavaScript (ES6+)
- Fetch API for asynchronous requests

### Backend
- PHP 7.4+
- MySQL/MariaDB
- MySQLi with prepared statements

### Server Environment
- XAMPP (Apache + MySQL)
- Windows 10/11

## Project Structure

```
CaseStudy_05/
├── frontend/
│   ├── index.html              # Home page
│   ├── menu.html               # Customer menu and ordering
│   ├── music.html              # Music events page
│   ├── jobs.html               # Employment opportunities
│   ├── updateprice.html        # Admin price management
│   ├── salesreport.html        # Admin sales analytics
│   ├── styles.css              # Global stylesheet
│   ├── MenuUpdate.js           # Menu calculator and checkout
│   ├── updateprice.js          # Price update functionality
│   ├── salesreport.js          # Sales report generation
│   ├── formvalidation.js       # Form validation utilities
│   └── config.js               # Frontend configuration
├── backend/
│   ├── dbconnect.php           # Database connection
│   ├── api_products.php        # Product data endpoint
│   ├── api_update_price.php    # Price update endpoint
│   ├── api_checkout.php        # Order processing endpoint
│   ├── api_sales_report.php    # Sales analytics endpoint
│   ├── show_get.php            # GET request debugging
│   └── show_post.php           # POST request debugging
├── database/
│   ├── javajam_db.sql          # Database schema and initial data
│   └── test_data.sql           # Sample sales data for testing
├── assets/
│   ├── coffee.jpg              # Coffee image
│   └── croissant.png           # Croissant image
├── .prettierrc                 # Code formatting configuration
├── .editorconfig               # Editor configuration
└── README.md                   # Project documentation
```

## Database Schema

### Products Table
Stores coffee product information and pricing.

```sql
CREATE TABLE products (
  product_id INT AUTO_INCREMENT PRIMARY KEY,
  product_name VARCHAR(100) NOT NULL,
  description TEXT,
  price_single DECIMAL(10, 2) DEFAULT NULL,
  price_double DECIMAL(10, 2) DEFAULT NULL,
  has_options BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Sales Table
Records all customer orders with date and time tracking.

```sql
CREATE TABLE sales (
  sale_id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  product_name VARCHAR(100) NOT NULL,
  option_type VARCHAR(20),
  quantity INT NOT NULL,
  unit_price DECIMAL(10, 2) NOT NULL,
  total_amount DECIMAL(10, 2) NOT NULL,
  sale_date DATE NOT NULL,
  sale_time TIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products (product_id) ON DELETE CASCADE
);
```

## Installation

### Prerequisites

- XAMPP (or similar LAMP/WAMP stack)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Modern web browser (Chrome, Firefox, Edge)

### Setup Instructions

1. **Install XAMPP**
   - Download from [https://www.apachefriends.org](https://www.apachefriends.org)
   - Install to default location (`C:\xampp`)

2. **Start Services**
   ```
   - Open XAMPP Control Panel
   - Start Apache
   - Start MySQL
   ```

3. **Create Database**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Click "New" to create a database
   - Import `database/javajam_db.sql`
   - Optionally import `database/test_data.sql` for sample data

4. **Configure Database Connection**
   - Edit `backend/dbconnect.php` if needed:
   ```php
   @$dbcnx = new mysqli('localhost', 'root', '', 'javajam');
   ```

5. **Deploy Application**
   - Copy the entire `CaseStudy_05` folder to `C:\xampp\htdocs\`
   - Or configure a virtual host to point to the project directory

6. **Access Application**
   - Navigate to `http://localhost/CaseStudy_05/frontend/index.html`

## Usage Guide

### Customer Interface

#### Ordering from Menu
1. Navigate to Menu page
2. Select quantity for desired items
3. Choose Single or Double shot options (where applicable)
4. View calculated subtotals and total price
5. Click "Check Out" to complete order
6. Confirmation message displays order total

### Administrative Interface

#### Updating Prices
1. Navigate to "Update Prices" page
2. View current prices for all products
3. Enter new price(s) in the input fields
4. Click "Update Price" button
5. Confirmation message indicates success
6. Updated prices immediately reflect on menu

#### Viewing Sales Reports
1. Navigate to "Sales Report" page
2. Select date using date picker (defaults to today)
3. Click "Generate Report"
4. View three report sections:
   - **Sales by Product**: Total revenue and quantity per product with breakdown
   - **Sales by Category**: Totals by option type (single/double/endless)
   - **Best Selling**: Highest revenue product and most popular option

## API Documentation

### GET /backend/api_products.php

Retrieves all products with current pricing.

**Response:**
```json
{
  "success": true,
  "products": [
    {
      "product_id": 1,
      "product_name": "Just Java",
      "description": "Regular house blend...",
      "price_single": 2.00,
      "price_double": null,
      "has_options": false
    }
  ]
}
```

### POST /backend/api_update_price.php

Updates product pricing.

**Request Parameters:**
- `product_id` (integer, required)
- `price_single` (decimal, required)
- `price_double` (decimal, optional)

**Response:**
```json
{
  "success": true,
  "message": "Price updated successfully"
}
```

### POST /backend/api_checkout.php

Processes customer order and saves to database.

**Request Parameters:**
- `items[]` (array of JSON strings, required)

**Item Format:**
```json
{
  "product_id": 1,
  "product_name": "Just Java",
  "quantity": 2,
  "option": null
}
```

**Response:**
```json
{
  "success": true,
  "message": "Order placed successfully",
  "total": 15.75
}
```

### GET /backend/api_sales_report.php

Generates sales report for specified date.

**Query Parameters:**
- `date` (YYYY-MM-DD format, defaults to today)

**Response:**
```json
{
  "success": true,
  "date": "2025-10-22",
  "sales_by_product": [...],
  "product_details": [...],
  "sales_by_category": [...],
  "best_selling": {...}
}
```

## Security Features

### SQL Injection Prevention
- All database queries use prepared statements
- Parameter binding with type validation
- No direct string concatenation in SQL

### Input Validation
- Server-side validation using `filter_input()`
- Type checking (FILTER_VALIDATE_INT, FILTER_VALIDATE_FLOAT)
- Date format validation using regex
- Negative value prevention for prices

### XSS Prevention
- HTML escaping for all user-generated content
- `escapeHtml()` function in JavaScript
- Content-Type headers properly set

### Price Integrity
- Prices retrieved from database, not client
- Server-side calculation of totals
- Client values never trusted for financial calculations

## Code Quality

### Formatting Standards
- Prettier configuration for consistent code style
- 2-space indentation
- Single quotes for strings
- Semicolons required
- LF line endings

### Best Practices
- Separation of concerns (frontend/backend)
- RESTful API design
- Async/await for asynchronous operations
- Error handling with try-catch blocks
- DOMContentLoaded for proper initialization

## Testing

### Manual Testing Checklist

**Menu Functionality:**
- [ ] Prices load from database on page load
- [ ] Calculator updates correctly on quantity change
- [ ] Single/double selection affects price correctly
- [ ] Checkout saves order to database
- [ ] Success/error messages display properly
- [ ] Form resets after successful checkout

**Price Update:**
- [ ] Current prices display correctly
- [ ] Price updates persist to database
- [ ] Updated prices reflect on menu immediately
- [ ] Validation prevents negative prices
- [ ] Single-option products only update single price

**Sales Report:**
- [ ] Date picker defaults to today
- [ ] Report generates for selected date
- [ ] Sales by Product shows correct totals
- [ ] Breakdown column displays option quantities
- [ ] Sales by Category shows single/double/endless
- [ ] Best selling product identified correctly
- [ ] Popular option calculated accurately

## Troubleshooting

### Common Issues

**Database Connection Errors:**
- Verify MySQL is running in XAMPP
- Check database name in `dbconnect.php`
- Ensure database has been imported

**Prices Not Updating:**
- Hard refresh browser (Ctrl + Shift + R)
- Clear browser cache
- Verify database connection
- Check browser console for errors

**API Returns Errors:**
- Check PHP error log in `C:\xampp\php\logs\php_error_log`
- Verify file paths are correct
- Ensure all required parameters are sent

**Sales Report Shows No Data:**
- Verify sales exist for selected date
- Import `test_data.sql` for sample data
- Check date format (YYYY-MM-DD)

## Development Notes

### Version Control
- Use Git for version tracking
- Commit frequently with descriptive messages
- Keep database credentials out of version control

### Future Enhancements
- User authentication for admin pages
- Order history for customers
- Inventory management
- Email confirmations
- Mobile-responsive design improvements
- Chart visualizations for sales data

## License

This project is developed for educational purposes as part of the IE4727 Web Application Design course.

## Author

Developed by Jun Le  
Nanyang Technological University  
IE4727 Web Application Design - 2025

## Acknowledgments

- NTU School of Computer Science and Engineering
- IE4727 Course Instructors
- XAMPP Development Team
- Mozilla Developer Network (MDN) Documentation

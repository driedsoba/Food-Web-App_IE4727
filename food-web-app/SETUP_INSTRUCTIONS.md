# Food Web App - Database Setup Guide

This guide will help you set up the database and backend for the Food Web App.

## Prerequisites

- **XAMPP** (or MAMP/WAMP) - Includes Apache web server, PHP, and MySQL
- **Node.js** and **npm** - For the React frontend

## Step 1: Install XAMPP

1. Download XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Install XAMPP with at least Apache and MySQL components
3. Start the **Apache** and **MySQL** services from the XAMPP Control Panel

## Step 2: Create the Database

### Option A: Using phpMyAdmin (Recommended)

1. Open your browser and go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Click on the **"New"** button in the left sidebar to create a new database
3. Enter `food_web_app` as the database name
4. Click **"Create"**
5. Select the `food_web_app` database from the left sidebar
6. Click on the **"Import"** tab at the top
7. Click **"Choose File"** and navigate to:
   ```
   food-web-app/backend/database/schema.sql
   ```
8. Click **"Go"** at the bottom to import the schema
9. After successful import, click **"Import"** again
10. Choose the seed data file:
    ```
    food-web-app/backend/database/seed_data.sql
    ```
11. Click **"Go"** to import the sample data

### Option B: Using MySQL Command Line

```bash
# Open MySQL command line from XAMPP or your terminal
mysql -u root -p

# Create the database
CREATE DATABASE food_web_app;
USE food_web_app;

# Import schema
SOURCE C:/Users/junle/OneDrive/Documents/GitHub/Food-Web-App_IE4727/food-web-app/backend/database/schema.sql;

# Import seed data
SOURCE C:/Users/junle/OneDrive/Documents/GitHub/Food-Web-App_IE4727/food-web-app/backend/database/seed_data.sql;
```

## Step 3: Configure the Backend

1. Verify the database connection settings in:
   ```
   food-web-app/backend/config/database.php
   ```
   
   Default settings:
   - **Host**: localhost
   - **Database**: food_web_app
   - **Username**: root
   - **Password**: (empty)

2. If your MySQL setup uses a different username/password, update these values in `database.php`

## Step 4: Set Up the Backend in Apache

### Option A: Copy Backend to htdocs (Recommended)

1. Copy the entire `backend` folder to your XAMPP `htdocs` directory:
   ```
   C:/xampp/htdocs/food-web-app/backend/
   ```

2. Your backend will now be accessible at:
   ```
   http://localhost/food-web-app/backend/api/
   ```

### Option B: Create a Virtual Host (Advanced)

Edit your Apache configuration to point to your project directory directly.

## Step 5: Configure Frontend API Base URL

1. Open `food-web-app/src/services/api.js`
2. Update the `API_BASE_URL` if needed:
   ```javascript
   const API_BASE_URL = 'http://localhost/food-web-app/backend/api';
   ```

## Step 6: Install Frontend Dependencies

```bash
cd food-web-app
npm install
```

## Step 7: Run the Frontend

```bash
npm run dev
```

The app should now be running at [http://localhost:5173](http://localhost:5173)

## Verify the Setup

1. **Check Backend APIs**:
   - Open [http://localhost/food-web-app/backend/api/menu_items.php](http://localhost/food-web-app/backend/api/menu_items.php)
   - You should see a JSON response with menu items

2. **Test Authentication**:
   - Use the test credentials:
     - **Username**: `testuser`
     - **Password**: `password123`
   - Try logging in through the Login page

3. **Check Menu Page**:
   - Navigate to the Menu page
   - Items should load from the database
   - Try search, filter, and sort functionality

## Database Structure

### Tables Created:
- **users** - User accounts with authentication
- **menu_items** - Restaurant menu items
- **cart_items** - Shopping cart items (supports both logged-in users and guest sessions)
- **feedbacks** - Customer reviews and ratings
- **orders** - Order records
- **order_items** - Individual items in orders

### Sample Data Included:
- 12 menu items (German cuisine)
- 1 test user account
- 3 approved sample feedbacks

## API Endpoints

### Authentication
- `POST /auth/login.php` - User login
- `POST /auth/register.php` - User registration
- `POST /auth/logout.php` - User logout
- `GET /auth/check-session.php` - Check current session

### Menu Items
- `GET /menu_items.php` - Get all active menu items (with filters)
- `POST /menu_items.php` - Create new menu item (admin)
- `PUT /menu_items.php` - Update menu item (admin)
- `DELETE /menu_items.php` - Delete menu item (admin)

### Cart
- `GET /cart.php` - Get cart items
- `POST /cart.php` - Add item to cart
- `PUT /cart.php` - Update cart item quantity
- `DELETE /cart.php` - Remove item from cart

### Feedback
- `GET /feedback.php` - Get approved feedbacks
- `POST /feedback.php` - Submit new feedback
- `PUT /feedback.php` - Approve feedback (admin)
- `DELETE /feedback.php` - Delete feedback (admin)

## Troubleshooting

### Database Connection Errors
- Make sure MySQL service is running in XAMPP
- Check database credentials in `backend/config/database.php`
- Verify the database name is `food_web_app`

### CORS Errors
- Ensure Apache is running
- Check that `backend/config/cors.php` allows your frontend origin
- Default allowed origin: `http://localhost:5173`

### API Returns 404
- Verify the backend folder is in the correct location (htdocs)
- Check the `API_BASE_URL` in `src/services/api.js`
- Ensure Apache is running on port 80

### Cannot Login
- Verify the database has been seeded with test data
- Check browser console for error messages
- Test the login API directly: `http://localhost/food-web-app/backend/api/auth/login.php`

### Menu Items Not Loading
- Check browser console for errors
- Test the menu API directly: `http://localhost/food-web-app/backend/api/menu_items.php`
- Verify seed data was imported successfully

## Security Notes

- **Production**: Change database credentials from default root user
- **Production**: Use environment variables for sensitive configuration
- **Production**: Implement proper admin authentication for admin-only endpoints
- **Production**: Enable HTTPS and update CORS settings
- **Development**: The current setup uses default credentials for ease of local development

## Next Steps

- Implement cart page UI
- Add order checkout functionality
- Create admin dashboard for managing menu items and orders
- Add more advanced search and filtering options
- Implement email notifications for orders

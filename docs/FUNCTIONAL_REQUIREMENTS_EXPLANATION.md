# Functional Requirements - How Each Function Works

## Overview
This document explains how each functional requirement is implemented in both the **Traditional (PHP)** and **Modern (React)** versions of the Food Web App.

---

## F1. User Authentication

### Traditional (PHP) Implementation
**Files:** `login.php`, `process-login.php`, `process-logout.php`, `validation.js`, `config.php`

**How it works:**
1. **Login/Register Page (`login.php`, lines 1-101)**
   - Single page with form toggle between login and registration
   - User enters credentials in HTML form
   - Client-side validation using `validation.js` (password complexity, email format)
   - Form submits via POST to `process-login.php`

2. **Authentication Processing (`process-login.php`, lines 1-138)**
   - **Registration Flow:**
     - Validates input (username length, email format, password complexity)
     - Checks for duplicate username/email
     - Hashes password using `password_hash()`
     - Inserts new user into database
     - Auto-login after successful registration
   - **Login Flow:**
     - Queries database for username
     - Verifies password using `password_verify()`
     - Creates session variables (`$_SESSION['user_id']`, `$_SESSION['username']`)
     - Redirects to menu page

3. **Logout (`process-logout.php`, lines 1-10)**
   - Destroys session using `session_destroy()`
   - Redirects to home page

4. **Session Management (`config.php`, lines 1-16)**
   - Starts PHP session on every page
   - Session data persists across page loads
   - Server-side session storage

**Flow Diagram:**
```
User → login.php (form) → POST → process-login.php → Database Check
                                                    ↓
                                              Set $_SESSION
                                                    ↓
                                            Redirect to menu.php
```

---

### Modern (React) Implementation
**Files:** `login.php`, `register.php`, `logout.php`, `check-session.php`, `api.js`

**How it works:**
1. **Frontend Components (`Login.jsx`, `Register.jsx`)**
   - Separate React components for login and registration
   - Form data managed by React state
   - Client-side validation before submission

2. **API Authentication (`api.js`, lines 31-59)**
   - `login()` function sends POST request to `login.php` backend
   - `register()` function sends POST request to `register.php`
   - Returns JSON responses with user data

3. **Backend API Endpoints:**
   - **`login.php` (lines 1-55):** Validates credentials, returns JSON with user data
   - **`register.php` (lines 9-110):** Creates new user, returns JSON response
   - **`logout.php` (lines 1-29):** Destroys session, returns JSON confirmation
   - **`check-session.php` (lines 1-40):** Checks if user is logged in, returns user data

4. **Session Persistence:**
   - Uses React Context API or localStorage
   - Frontend checks session on page load
   - API calls include credentials for authentication

**Flow Diagram:**
```
React Component → api.login() → HTTP POST → login.php → Database
                                                       ↓
                                                  JSON Response
                                                       ↓
                                            Update React State/Context
```

**Key Difference:**
- Traditional: Server-side sessions, full page reloads
- Modern: RESTful API, JSON responses, no page reloads

---

## F2. Menu Browsing and Ordering

### Traditional (PHP) Implementation
**Files:** `menu.php`, `process-add-to-cart.php`

**How it works:**
1. **Menu Display (`menu.php`, lines 1-160)**
   - **Server-side Query Building:**
     - Fetches all menu items from database on page load
     - Applies search filter if form submitted
     - Applies category filter (Mains, Sides, Desserts, Beverages)
     - Applies sorting (price, popularity, rating)
   - **HTML Generation:**
     - PHP loops through results and generates HTML cards
     - Each card has an "Add to Cart" form button
     - Form submits menu_item_id via POST

2. **Add to Cart (`process-add-to-cart.php`, lines 1-70)**
   - Receives menu_item_id from POST
   - Checks if user is logged in
   - Checks if item already in user's cart
   - If exists: Increment quantity by 1
   - If not: Insert new cart item with quantity 1
   - Redirects back to menu page

**Flow Diagram:**
```
menu.php → Load from DB → Display items
              ↓
        User clicks "Add to Cart"
              ↓
        POST to process-add-to-cart.php
              ↓
        Update cart_items table
              ↓
        Redirect back to menu.php
```

---

### Modern (React) Implementation
**Files:** `menu_items.php`, `Menu.jsx`, `api.js`

**How it works:**
1. **Backend API (`menu_items.php`, lines 6-154)**
   - RESTful endpoint that returns menu items as JSON
   - Supports query parameters for filtering/sorting
   - Returns structured JSON array of menu items

2. **Frontend Component (`Menu.jsx`, lines 42-225)**
   - **State Management:**
     - Stores menu items in React state
     - Manages search term, category filter, sort option
   - **Data Fetching:**
     - Calls `api.fetchMenuItems()` on component mount
     - Updates state with received data
   - **Client-side Filtering:**
     - Filters items in JavaScript based on user input
     - No page reload needed
   - **Add to Cart:**
     - Calls `api.addToCart()` when button clicked
     - Updates cart state immediately (optimistic update)

3. **API Functions (`api.js`, lines 61-76, 119-124)**
   - `fetchMenuItems()`: GET request to `menu_items.php`
   - `addToCart()`: POST request to `cart.php` with item data

**Flow Diagram:**
```
Menu.jsx mounts → api.fetchMenuItems() → GET menu_items.php → JSON Response
                                                              ↓
                                                      Update React State
                                                              ↓
                                                        Render UI
User clicks Add → api.addToCart() → POST cart.php → Update cart state
```

**Key Difference:**
- Traditional: Server renders HTML, form submissions, page reloads
- Modern: Client-side rendering, AJAX calls, dynamic updates

---

## F3. Shopping Cart

### Traditional (PHP) Implementation
**Files:** `cart.php`, `process-update-cart.php`, `process-remove-from-cart.php`

**How it works:**
1. **Cart Display (`cart.php`, lines 1-98)**
   - Checks if user is logged in
   - Queries database for user's cart items
   - JOINs with menu_items table to get item details
   - Displays items in HTML table
   - Each item has:
     - Quantity update form (text input + submit)
     - Remove button form
   - Shows subtotal, tax (10%), delivery fee ($5)

2. **Update Quantity (`process-update-cart.php`, lines 1-52)**
   - Receives cart_id and new quantity via POST
   - Validates ownership (user_id matches)
   - Updates quantity in database
   - Redirects back to cart page

3. **Remove Item (`process-remove-from-cart.php`, lines 1-38)**
   - Receives cart_id via POST
   - Validates ownership
   - Deletes item from cart_items table
   - Redirects back to cart page

**Flow Diagram:**
```
cart.php → Query cart_items → Display items
            ↓
    User updates quantity
            ↓
    POST to process-update-cart.php
            ↓
    UPDATE cart_items table
            ↓
    Redirect to cart.php (shows updated cart)
```

---

### Modern (React) Implementation
**Files:** `cart.php`, `api.js`, `Cart.jsx`

**How it works:**
1. **Backend API (`cart.php`, lines 13-148)**
   - Endpoints for GET, POST, PUT, DELETE operations
   - Returns cart data as JSON
   - Handles cart operations and returns updated cart

2. **Frontend Component (`Cart.jsx`, lines 22-360)**
   - **State Management:**
     - Stores cart items in React state
     - Calculates totals in real-time
   - **Operations:**
     - Update quantity: Calls `api.updateCartItem()`, updates state
     - Remove item: Calls `api.removeFromCart()`, updates state
     - All changes happen without page reload
   - **Optimistic Updates:**
     - UI updates immediately
     - Reverts if API call fails

3. **API Functions (`api.js`, lines 104-108)**
   - `getCart()`: GET request to `cart.php`
   - `updateCartItem()`: PUT request with new quantity
   - `removeFromCart()`: DELETE request

**Flow Diagram:**
```
Cart.jsx loads → api.getCart() → GET cart.php → JSON Response
                                              ↓
                                        Update State
                                              ↓
User changes qty → Optimistic update → api.updateCartItem() → PUT cart.php
                                                             ↓
                                                        Confirm update
```

**Key Difference:**
- Traditional: Each action reloads entire page
- Modern: Real-time updates, single-page experience

---

## F4. Checkout and Order Placement

### Traditional (PHP) Implementation
**Files:** `cart.php` (100-150), `process-checkout.php`, `order-confirmation.php`

**How it works:**
1. **Checkout Form (`cart.php`, lines 100-150)**
   - Delivery information form at bottom of cart page
   - Fields: name, email, phone, delivery address
   - Form submits directly to `process-checkout.php`

2. **Order Processing (`process-checkout.php`, lines 1-119)**
   - **Validation:**
     - All fields required
     - Email format check
     - Phone: exactly 8 digits
     - Address: minimum 10 characters
   - **Order Creation:**
     - Begins database transaction
     - Calculates total (subtotal × 1.1 + $5)
     - Inserts order into `orders` table
     - Inserts items into `order_items` table
     - Clears user's cart
     - Commits transaction
   - **Success:**
     - Redirects to order-confirmation.php with order_id

3. **Confirmation Page (`order-confirmation.php`, lines 1-121)**
   - Retrieves order details using order_id from URL
   - Displays order summary
   - Shows estimated delivery time
   - Provides links to order history

**Flow Diagram:**
```
cart.php (form) → POST → process-checkout.php
                            ↓
                    Validate input
                            ↓
                    BEGIN TRANSACTION
                            ↓
                    INSERT into orders
                            ↓
                    INSERT into order_items
                            ↓
                    DELETE from cart_items
                            ↓
                    COMMIT
                            ↓
                    Redirect to order-confirmation.php?order_id=X
```

---

### Modern (React) Implementation
**Files:** `orders.php`, `cart.php`, `api.js`, `Checkout.jsx`

**How it works:**
1. **Checkout Component (`Checkout.jsx`, lines 74-218)**
   - Separate checkout page/component
   - Form fields managed by React state
   - Client-side validation before submission
   - Calls `api.createOrder()` on submit

2. **API Functions (`api.js`, lines 200-207)**
   - `createOrder()`: POST request to `orders.php`
   - Sends delivery info and cart data as JSON
   - Receives order_id in response

3. **Backend API (`orders.php`, lines 87-164)**
   - Receives JSON data
   - Performs same validation and transaction as traditional
   - Returns JSON response with order details
   - Also serves as API for order retrieval

4. **Cart API (`cart.php`, lines 162-204)**
   - May handle clearing cart after successful order
   - Works with orders.php to complete checkout process

**Flow Diagram:**
```
Checkout.jsx → User fills form → api.createOrder()
                                        ↓
                                POST orders.php (JSON)
                                        ↓
                                Validate & Create Order
                                        ↓
                                JSON Response {order_id, status}
                                        ↓
                                Navigate to confirmation page
```

**Key Difference:**
- Traditional: Form POST, server validation, redirect
- Modern: JSON API, client-side validation, programmatic navigation

---

## F5. Order Status Tracking

### Traditional (PHP) Implementation
**Files:** `order-history.php`, `process-advance-order.php`

**How it works:**
1. **Order Display (`order-history.php`, lines 1-119)**
   - Fetches all orders for logged-in user
   - Displays each order with:
     - Order number, date, status badge
     - List of items
     - "Advance Status" button (if not delivered/cancelled)
   - Status badge color-coded by status

2. **Status Progression (`process-advance-order.php`, lines 1-66)**
   - **Status Flow:**
     ```
     order placed → preparing → out for delivery → delivered
     ```
   - Receives order_id via POST
   - Verifies ownership (user_id matches)
   - Finds current status in progression array
   - Updates to next status
   - Redirects back to order-history.php

**Flow Diagram:**
```
order-history.php → Display orders with current status
                            ↓
                User clicks "Advance Status"
                            ↓
        POST to process-advance-order.php
                            ↓
        Find current status in array
                            ↓
        Get next status
                            ↓
        UPDATE orders SET status = next_status
                            ↓
        Redirect back to order-history.php
```

**Status Progression:**
1. **order placed** → 2. **preparing** → 3. **out for delivery** → 4. **delivered**

---

### Modern (React) Implementation
**Files:** `orders.php`, `OrderStatus.jsx`

**How it works:**
1. **Backend API (`orders.php`, lines 56-85)**
   - Endpoint to update order status
   - Receives order_id and potentially new status
   - Validates ownership
   - Returns updated order as JSON

2. **Frontend Component (`OrderStatus.jsx`, lines 1-138)**
   - Displays current order status with visual indicator
   - May show progress bar or stepper component
   - "Advance" button calls `api.advanceOrderStatus()`
   - Updates UI immediately (optimistic update)
   - Can also poll for real-time status updates

**Flow Diagram:**
```
OrderStatus.jsx → Display current status
                        ↓
            User clicks "Advance"
                        ↓
            api.advanceOrderStatus()
                        ↓
            PUT orders.php (JSON)
                        ↓
            Update database
                        ↓
            JSON Response {updated order}
                        ↓
            Update React state
                        ↓
            UI reflects new status (no reload)
```

**Key Difference:**
- Traditional: Page reload to see status change
- Modern: Real-time UI update, may include animations

---

## F6. Order History Viewing

### Traditional (PHP) Implementation
**Files:** `order-history.php`

**How it works:**
1. **Authentication Check**
   - Requires user to be logged in
   - Redirects to login if not authenticated

2. **Data Retrieval (`order-history.php`, lines 1-119)**
   - Queries orders table for user's orders
   - JOINs with order_items and menu_items
   - Orders sorted by created_at DESC (newest first)
   - Groups items per order using nested query

3. **Display:**
   - Each order shows:
     - Order number and date
     - Status badge (color-coded)
     - List of items with quantities
     - Total amount
     - Action buttons (Advance Status, View Details)
   - Empty state if no orders

**SQL Query Structure:**
```sql
SELECT o.*, COUNT(oi.id) as item_count
FROM orders o
LEFT JOIN order_items oi ON o.id = oi.order_id
WHERE o.user_id = ?
GROUP BY o.id
ORDER BY o.created_at DESC
```

**Flow Diagram:**
```
User visits order-history.php
        ↓
Check if logged in
        ↓
Query database for user's orders
        ↓
For each order, query order_items
        ↓
Render HTML with order cards
```

---

### Modern (React) Implementation
**Files:** `order-history.php`

**How it works:**
1. **Backend API (`order-history.php`, lines 1-73, 316-396)**
   - RESTful endpoint returning orders as JSON
   - Filters by user_id from session
   - Returns complete order data including items

2. **Frontend Component (`OrderHistory.jsx`)**
   - Calls `api.getOrders()` on mount
   - Stores orders in React state
   - Maps over orders to render order cards
   - Each order card is a reusable component
   - Can filter/sort on client side

3. **Features:**
   - Real-time updates (can refresh without reload)
   - Search/filter orders by date or status
   - Pagination for large order lists
   - Click order to expand details

**Flow Diagram:**
```
OrderHistory.jsx mounts
        ↓
api.getOrders() → GET order-history.php
        ↓
JSON Response: [{order1}, {order2}, ...]
        ↓
Update React state
        ↓
Map orders to OrderCard components
        ↓
Render list
```

**Key Difference:**
- Traditional: Server renders complete HTML
- Modern: Client renders from JSON data, more interactive

---

## F7. Customer Feedback Submission

### Traditional (PHP) Implementation
**Files:** `feedback.php`, `process-feedback.php`

**How it works:**
1. **Feedback Page (`feedback.php`, lines 1-167)**
   - **Form Section:**
     - Name, email (pre-filled if logged in)
     - Order number (optional)
     - Rating dropdown (1-5 stars)
     - Feedback text area
     - Submits to `process-feedback.php`
   - **Display Section:**
     - Queries feedbacks WHERE approved = 1
     - Shows approved feedbacks with star ratings
     - Displays author name, rating, feedback text, date

2. **Feedback Processing (`process-feedback.php`, lines 1-52)**
   - Receives form data via POST
   - Validates:
     - All required fields present
     - Rating between 1-5
     - Email format
   - Inserts feedback with **approved = 0** (pending approval)
   - Redirects back to feedback.php with success message

3. **Approval Process:**
   - Admin must manually set approved = 1 in database
   - Only approved feedbacks display on page

**Flow Diagram:**
```
feedback.php → Display form + approved feedbacks
                    ↓
        User submits feedback
                    ↓
        POST to process-feedback.php
                    ↓
        Validate input
                    ↓
        INSERT INTO feedbacks (approved=0)
                    ↓
        Redirect with success message
                    ↓
        Admin manually approves
                    ↓
        Feedback appears on page
```

---

### Modern (React) Implementation
**Files:** `feedback.php`, `database.php`, `cors.php`, `api.js`, `Feedback.jsx`

**How it works:**
1. **Backend API (`feedback.php`, lines 6-135)**
   - GET endpoint: Returns approved feedbacks as JSON
   - POST endpoint: Receives feedback submission
   - CORS enabled via `cors.php` (lines 1-19)
   - Database operations via `database.php` (lines 1-30)

2. **Frontend Component (`Feedback.jsx`, lines 1-269)**
   - **Form Section:**
     - Controlled components (React state)
     - Rating input (star selector)
     - Client-side validation
     - Calls `api.submitFeedback()` on submit
   - **Display Section:**
     - Fetches feedbacks on mount
     - Renders feedback cards dynamically
     - May include pagination

3. **API Functions (`api.js`, lines 167-180)**
   - `getFeedbacks()`: GET request to `feedback.php`
   - `submitFeedback()`: POST request with JSON data

**Flow Diagram:**
```
Feedback.jsx mounts
        ↓
api.getFeedbacks() → GET feedback.php
        ↓
JSON Response: approved feedbacks
        ↓
Render feedback list
        ↓
User submits form → api.submitFeedback()
        ↓
POST feedback.php (JSON)
        ↓
Insert into database (approved=0)
        ↓
Success response
        ↓
Show success message (no reload)
```

**Key Difference:**
- Traditional: Form POST, page reload
- Modern: AJAX submission, immediate feedback, no reload

---

## F8. Catering Package

### Traditional (PHP) Implementation
**Files:** `catering.php`

**How it works:**
1. **Package Display (`catering.php`, lines 1-116)**
   - Static HTML table comparing packages
   - Three tiers: Essential, Premium, Deluxe
   - Features comparison:
     - Price per person
     - Main course options
     - Side dishes, appetizers, desserts
     - Setup type, tableware, service staff
   - Contact section with email address
   - No form submission (users email directly)

**Structure:**
```html
<table>
  <thead>
    <tr>
      <th>Feature</th>
      <th>Essential</th>
      <th>Premium</th>
      <th>Deluxe</th>
    </tr>
  </thead>
  <tbody>
    <!-- Feature comparison rows -->
  </tbody>
</table>
```

---

### Modern (React) Implementation
**Files:** `Catering.jsx`

**How it works:**
1. **Component (`Catering.jsx`, lines 68-147)**
   - Data stored in JavaScript array/object
   - Maps over package data to render comparison
   - May include interactive features:
     - Hover effects
     - Expandable sections
     - Package selector
   - Styled with CSS-in-JS or styled-components

**Data Structure:**
```javascript
const packages = [
  {
    name: 'Essential',
    price: '$15-20',
    features: ['3 main options', '2 sides', ...]
  },
  // ...
]
```

**Key Difference:**
- Traditional: Static HTML table
- Modern: Dynamic rendering, more interactive

---

## F9. Email for Catering

### Modern Implementation
**Files:** `catering.php`, `Catering.jsx`

**How it works:**
1. **Backend (`catering.php`, lines 8-70)**
   - Handles POST request with inquiry form data
   - Validates input:
     - Required fields
     - Email format
     - Phone number (8 digits)
     - Guest count (positive number)
   - Sends email using PHP `mail()` function
   - Email contains:
     - Customer name, email, phone
     - Event type, date, location
     - Number of guests
     - Custom message
   - Returns JSON success/error response

2. **Frontend (`Catering.jsx`, lines 4-66, 149-303)**
   - **Inquiry Form (lines 149-303):**
     - Name, email, phone inputs
     - Event type dropdown
     - Event date picker
     - Guest count number input
     - Location and message fields
   - **Submission (lines 4-66):**
     - Form data managed in React state
     - Validates before submission
     - Calls `api.submitCateringInquiry()`
     - Shows success/error message

**Email Flow:**
```
User fills form → Submit
        ↓
api.submitCateringInquiry()
        ↓
POST catering.php (JSON)
        ↓
Validate data
        ↓
Compose email body
        ↓
mail($to, $subject, $body, $headers)
        ↓
JSON Response {success: true}
        ↓
Show confirmation message
```

**Email Template:**
```
Subject: Catering Request from [Name]

New Catering Request:

Name: [Name]
Email: [Email]
Phone: [Phone]
Event Type: [Type]
Event Date: [Date]
Number of Guests: [Count]
Location: [Location]

Message:
[Custom message]
```

**Note on localhost:**
- PHP `mail()` function requires mail server configuration
- On localhost, may need to:
  - Configure sendmail
  - Use PHPMailer library
  - Use SMTP service (like Mailtrap for testing)

---

## Summary Comparison Table

| Feature | Traditional (PHP) | Modern (React) |
|---------|------------------|----------------|
| **Architecture** | Server-side rendering | Client-side rendering + API |
| **Data Flow** | Form POST → Process → Redirect | AJAX → JSON → State Update |
| **User Experience** | Page reloads | Single-page, no reloads |
| **Validation** | Server-side (+ some client JS) | Client-side + server-side |
| **State Management** | PHP Sessions | React State/Context |
| **Rendering** | Server generates HTML | Client renders from JSON |
| **Real-time Updates** | Requires page refresh | Instant UI updates |
| **Code Organization** | PHP files (logic + view) | Separated (API + Components) |

---

## Key Concepts Explained

### Traditional (PHP) Approach:
1. **Request-Response Cycle:** Every action = new HTTP request
2. **Server Rendering:** Server generates complete HTML
3. **Session Management:** PHP `$_SESSION` superglobal
4. **Form Submissions:** POST data, process, redirect (PRG pattern)
5. **Database Queries:** Direct SQL in PHP files

### Modern (React) Approach:
1. **Single Page Application (SPA):** One HTML load, dynamic updates
2. **RESTful API:** Backend = JSON endpoints
3. **State Management:** React hooks, Context API
4. **AJAX Requests:** Fetch/Axios for async communication
5. **Component Architecture:** Reusable UI components

---

## Presentation Tips

### For Each Function, Explain:

1. **User Journey:**
   - What does the user see/do?
   - Step-by-step interaction

2. **Data Flow:**
   - Where does data come from?
   - How is it processed?
   - Where does it go?

3. **Code Walkthrough:**
   - Show key files
   - Highlight important lines
   - Explain validation/logic

4. **Differences:**
   - Traditional vs Modern approach
   - Advantages/disadvantages
   - User experience impact

5. **Demo:**
   - Live demonstration
   - Show both versions side-by-side
   - Highlight the differences in action

---

## Common Questions to Prepare For

1. **Why two implementations?**
   - Educational: Show different web development approaches
   - Traditional: Foundation understanding
   - Modern: Industry standard

2. **Which is better?**
   - Depends on requirements
   - Traditional: Simpler, works without JavaScript
   - Modern: Better UX, more interactive, scalable

3. **Security considerations?**
   - Both use password hashing
   - Both validate input
   - Modern has CORS considerations
   - Traditional has CSRF token considerations

4. **Performance?**
   - Traditional: Server load for every action
   - Modern: Initial load heavier, then faster interactions

5. **Scalability?**
   - Traditional: More server resources per user
   - Modern: Backend is just API, can scale independently

---

**Good luck with your presentation!** 🚀

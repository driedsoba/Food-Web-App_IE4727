# Functional Requirements Mapping - food-web-app-php

This document maps each functional requirement to the specific files and line numbers where the functionality is implemented.

---

## F1: User Authentication (Login and Registration)
**Lines: login.php (1-103), process-login.php (1-139), process-logout.php (1-8)**

### Files Involved:

#### 1. `login.php` (103 lines)
**Purpose:** Login/Registration page with combined form
- **Lines 1-6:** Session initialization and redirect if already logged in
- **Lines 8-18:** Error/success message display from session
- **Lines 20-28:** Page header with form toggle instructions
- **Lines 30-36:** Username/Full name input field (conditional label based on mode)
- **Lines 38-44:** Email input field (shown only for registration)
- **Lines 46-52:** Password input field with conditional placeholder
- **Lines 54-63:** Confirm password field (shown only for registration)
- **Lines 65-75:** Submit button (text changes based on mode)
- **Lines 77-91:** Form mode toggle with hidden input
- **Lines 93-103:** JavaScript for form toggle functionality

#### 2. `backend/process-login.php` (139 lines)
**Purpose:** Backend processor for both login and registration

**Registration Logic:**
- **Lines 11-24:** Extract and validate registration form data
- **Lines 26-29:** Check username length (minimum 3 characters)
- **Lines 31-34:** Validate email format
- **Lines 36-39:** Check password length (minimum 8 characters)
- **Lines 41-44:** Validate password complexity (letter + number + special character)
- **Lines 46-49:** Check password confirmation match
- **Lines 51-62:** Check for duplicate username
- **Lines 64-75:** Check for duplicate email
- **Lines 77-83:** Hash password and prepare user data
- **Lines 85-89:** Insert new user into database
- **Lines 91-93:** Auto-login after successful registration

**Login Logic:**
- **Lines 95-103:** Extract and validate login credentials
- **Lines 105-116:** Query user by username
- **Lines 118-124:** Verify password hash
- **Lines 126-130:** Set session variables upon successful login
- **Lines 132-134:** Redirect to menu page

#### 3. `backend/process-logout.php` (8 lines)
**Purpose:** Destroy user session and redirect
- **Lines 1-3:** Include config (starts session)
- **Lines 5-6:** Destroy all session data
- **Lines 7-8:** Redirect to index page

#### 4. `js/validation.js` - Password Validation Function
**Purpose:** Client-side password validation
- **Lines for validatePassword():** Check 8+ chars, letter, number, special character

#### 5. `includes/config.php` - Session Management
**Purpose:** Initialize session and define constants
- Session start functionality
- Site name configuration

#### 6. `includes/header.php` - Authentication UI
**Purpose:** Display login/logout buttons based on session state
- Check `$_SESSION['user_id']` for logged-in state
- Show username and logout button if logged in
- Show login button if not logged in

---

## F2: Menu Browsing and Ordering (Add to Cart)
**Lines: menu.php (1-167), process-add-to-cart.php (1-70)**

### Files Involved:

#### 1. `menu.php` (167 lines)
**Purpose:** Display menu items with search, filter, and sort

**Query Building:**
- **Lines 8-16:** Base SQL query with search parameter
- **Lines 18-26:** Category filter logic
- **Lines 28-33:** Sort order logic (price, popularity, rating)
- **Lines 35-44:** Execute query and fetch menu items

**Menu Display:**
- **Lines 59-68:** Search form with text input
- **Lines 70-79:** Sort dropdown (Price, Popularity, Rating)
- **Lines 81-119:** Category tabs (All, Mains, Sides, Desserts, Beverages)
- **Lines 121-126:** Alert message display area
- **Lines 128-162:** Menu grid
  - **Lines 132-137:** Menu item image
  - **Lines 139-141:** Item name
  - **Lines 143-145:** Item price
  - **Lines 147-159:** Add to cart form (submits to process-add-to-cart.php)

#### 2. `backend/process-add-to-cart.php` (70 lines)
**Purpose:** Add items to cart (increment if exists)

- **Lines 1-3:** Include config and database
- **Lines 5-8:** Verify POST request
- **Lines 10-14:** Check user authentication
- **Lines 16-21:** Extract and validate menu_item_id
- **Lines 23-28:** Verify menu item exists
- **Lines 30-42:** Check if item already in cart
- **Lines 44-53:** If exists, increment quantity by 1
- **Lines 55-65:** If not exists, insert new cart item with quantity 1
- **Lines 67-70:** Redirect back to menu

---

## F3: Shopping Cart Management
**Lines: cart.php (1-144), process-update-cart.php (1-48), process-remove-from-cart.php (1-38)**

### Files Involved:

#### 1. `cart.php` (144 lines)
**Purpose:** Display cart and manage items

**Cart Display:**
- **Lines 8-12:** Check user authentication
- **Lines 14-34:** Fetch cart items with JOIN to menu_items
- **Lines 36-41:** Alert message display
- **Lines 43-52:** Empty cart message (if no items)
- **Lines 54-94:** Cart items table
  - **Lines 68-69:** Item image
  - **Lines 70-71:** Item name
  - **Lines 72-73:** Item price
  - **Lines 74-86:** Quantity update form (submits to process-update-cart.php)
  - **Lines 87-92:** Remove button form (submits to process-remove-from-cart.php)

**Order Summary:**
- **Lines 96-109:** Calculate subtotal, tax (10%), delivery ($5), total
- **Lines 111-116:** Display calculated amounts

**Checkout Form:**
- **Lines 118-142:** Delivery information form (submits to process-checkout.php)
  - **Lines 122-123:** Customer name input
  - **Lines 125-126:** Email input
  - **Lines 128-129:** Phone input (8 digits required)
  - **Lines 131-132:** Delivery address textarea (10+ chars required)
  - **Lines 134-138:** Place Order button

#### 2. `backend/process-update-cart.php` (48 lines)
**Purpose:** Update cart item quantity

- **Lines 1-3:** Include config and database
- **Lines 5-8:** Verify POST request
- **Lines 10-14:** Check user authentication
- **Lines 16-21:** Extract and validate cart_id and quantity
- **Lines 23-33:** Verify cart item ownership
- **Lines 35-42:** Update quantity in database
- **Lines 44-48:** Redirect to cart

#### 3. `backend/process-remove-from-cart.php` (38 lines)
**Purpose:** Remove item from cart

- **Lines 1-3:** Include config and database
- **Lines 5-8:** Verify POST request
- **Lines 10-14:** Check user authentication
- **Lines 16-21:** Extract and validate cart_id
- **Lines 23-32:** Delete cart item (with user_id verification)
- **Lines 34-38:** Redirect to cart

#### 4. `backend/process-clear-cart.php` (if exists)
**Purpose:** Clear entire cart
- Clear all cart items for user_id

---

## F4: Checkout and Order Placement
**Lines: cart.php (118-142), process-checkout.php (1-119), order-confirmation.php (1-116)**

### Files Involved:

#### 1. `cart.php` (144 lines)
**Purpose:** Delivery information form

**Checkout Form:**
- **Lines 118-142:** Complete delivery form
  - **Lines 122-123:** Customer name input
  - **Lines 125-126:** Email input
  - **Lines 128-129:** Phone number input
  - **Lines 131-132:** Delivery address textarea
  - **Lines 134-138:** Place Order submit button
  - Form action: `backend/process-checkout.php`

#### 2. `backend/process-checkout.php` (119 lines)
**Purpose:** Process order and create order records

**Validation:**
- **Lines 5-8:** Verify POST request
- **Lines 10-13:** Extract form data
- **Lines 15-20:** Check all required fields
- **Lines 22-26:** Validate email format
- **Lines 28-33:** Validate address length (minimum 10 characters)
- **Lines 35-40:** Validate phone number (exactly 8 digits)

**Order Processing:**
- **Lines 42-44:** Get user_id from session
- **Lines 46-60:** Fetch cart items with prices
- **Lines 62-66:** Calculate total (subtotal × 1.1 + $5)
- **Lines 68-70:** Begin database transaction

**Database Operations:**
- **Lines 72-81:** Insert order record
- **Lines 83-92:** Insert order_items for each cart item
- **Lines 94-100:** Clear cart after successful order
- **Lines 102-105:** Commit transaction and redirect to confirmation
- **Lines 107-112:** Rollback on error

#### 3. `order-confirmation.php` (116 lines)
**Purpose:** Display order confirmation

**Order Retrieval:**
- **Lines 7-14:** Get and validate order_id from URL
- **Lines 16-26:** Fetch order details with items (GROUP_CONCAT)
- **Lines 28-32:** Check if order exists
- **Lines 34-44:** Verify user access (own order or guest)

**Display:**
- **Lines 51-60:** Success icon (SVG checkmark)
- **Lines 62-63:** Success heading
- **Lines 66-91:** Order details box
  - **Lines 68-70:** Order number
  - **Lines 71-75:** Order status badge
  - **Lines 76-78:** Total amount
  - **Lines 79-81:** Delivery address
  - **Lines 82-84:** Contact phone
  - **Lines 85-87:** Estimated delivery time
- **Lines 89-98:** What's next section (bullet points)
- **Lines 100-106:** Action buttons (Order History, Order More, Home)
- **Lines 108-111:** Support contact info

---

## F5: Order Status Tracking (Advance Order Status)
**Lines: order-history.php (99-103), process-advance-order.php (1-63)**

### Files Involved:

#### 1. `order-history.php` (115 lines)
**Purpose:** Display orders with status tracking

**Order Display:**
- **Lines 59-106:** Loop through each order
  - **Lines 68-73:** Fetch order items for each order
  - **Lines 77-85:** Order header with order number, date, status badge
  - **Lines 87-96:** Order items list
  - **Lines 98-104:** Order footer with total and action buttons
    - **Lines 99-103:** Advance Status button (shown if not delivered/cancelled)
    - Form submits to `backend/process-advance-order.php`

#### 2. `backend/process-advance-order.php` (63 lines)
**Purpose:** Advance order to next status

**Validation:**
- **Lines 5-8:** Verify POST request
- **Lines 10-14:** Check user authentication
- **Lines 16-21:** Extract and validate order_id
- **Lines 23-35:** Verify order ownership

**Status Progression:**
- **Lines 37-45:** Define status progression array
  - `['order placed', 'preparing', 'out for delivery', 'delivered']`
- **Lines 47-52:** Find current status index and check if can advance
- **Lines 54-55:** Get next status

**Database Update:**
- **Lines 57-62:** Update order status in database
- **Lines 64-69:** Set success/error message and redirect

---

## F6: Order History Viewing
**Lines: order-history.php (1-115)**

### Files Involved:

#### 1. `order-history.php` (115 lines)
**Purpose:** Display all user orders

**Authentication:**
- **Lines 5-9:** Require login, redirect if not authenticated

**Data Retrieval:**
- **Lines 14-22:** Fetch all orders for user with item count
  - JOIN with order_items
  - COUNT items per order
  - ORDER BY created_at DESC

**Display:**
- **Lines 45-50:** Empty state message (no orders)
- **Lines 51-106:** Orders list
  - **Lines 59-62:** For each order, fetch detailed items
  - **Lines 77-85:** Order card header
    - Order number
    - Order date (formatted)
    - Status badge with color coding
  - **Lines 87-96:** Order items list with quantities and prices
  - **Lines 98-104:** Order footer
    - Total amount
    - Advance Status button (conditional)
    - View Details link to order-confirmation.php

**Status Badge Styling:**
- Different colors based on status:
  - `order-placed`: Orange
  - `preparing`: Blue
  - `out-for-delivery`: Purple
  - `delivered`: Green
  - `cancelled`: Red

---

## F7: Customer Feedback Submission
**Lines: feedback.php (1-167), process-feedback.php (1-48)**

### Files Involved:

#### 1. `feedback.php` (167 lines)
**Purpose:** Feedback form and display approved feedbacks

**User Data Pre-fill:**
- **Lines 8-17:** If logged in, fetch user's name and email from database

**Feedback Form:**
- **Lines 50-104:** Feedback submission form
  - **Lines 55-60:** Customer name input (pre-filled if logged in)
  - **Lines 62-67:** Email input (pre-filled if logged in)
  - **Lines 69-72:** Order number input (optional)
  - **Lines 74-84:** Rating dropdown (1-5 stars)
  - **Lines 86-92:** Comment textarea (required)
  - **Lines 94-96:** Submit button
  - Form action: `backend/process-feedback.php`

**Approved Feedbacks Display:**
- **Lines 19-26:** Query approved feedbacks (WHERE approved = 1)
- **Lines 116-146:** Display feedbacks section
  - **Lines 120-144:** Loop through each feedback
    - **Lines 124-130:** Author name and star rating display
    - **Lines 131-133:** Feedback date
    - **Lines 135-136:** Feedback text

#### 2. `backend/process-feedback.php` (48 lines)
**Purpose:** Process feedback submission

**Validation:**
- **Lines 5-8:** Verify POST request
- **Lines 10-14:** Extract form data
- **Lines 16-20:** Check required fields
- **Lines 22-26:** Validate rating (1-5)
- **Lines 28-32:** Validate email format

**Database Insert:**
- **Lines 34-36:** Get user_id if logged in
- **Lines 38-42:** Insert feedback with approved = 0 (requires admin approval)
- **Lines 44-48:** Set success message and redirect

**Note:** Feedbacks are initially set to `approved = 0` and require manual approval before appearing on the page.

---

## F8: Catering Package Information
**Lines: catering.php (1-98)**

### Files Involved:

#### 1. `catering.php` (98 lines)
**Purpose:** Display catering packages and contact information

**Package Comparison Table:**
- **Lines 28-98:** Complete package comparison
  - **Lines 32-39:** Table header (Essential, Premium, Deluxe)
  - **Lines 41-97:** Feature rows:
    - **Lines 42-46:** Price per person
    - **Lines 47-51:** Main course options
    - **Lines 52-56:** Side dishes
    - **Lines 57-61:** Appetizers
    - **Lines 62-66:** Desserts
    - **Lines 67-71:** Setup type
    - **Lines 72-76:** Tableware quality
    - **Lines 77-81:** Service staff (Deluxe only)
    - **Lines 82-86:** Custom menu (Deluxe only)

**Contact Information:**
- **Lines 88-96:** Contact section with catering email
  - Email: `catering@leckerhaus.com`

**Note:** This page is display-only. Users are directed to email for inquiries rather than using an online form.

---

## F9: Catering Inquiry Email
**Lines: process-catering.php (1-70)**

### Files Involved:

#### 1. `backend/process-catering.php` (70 lines)
**Purpose:** Process catering inquiry and send email

**Note:** The current implementation does not have a form on catering.php. The file process-catering.php exists but is not currently used. If a form were added, here's what it would do:

**Validation (if form existed):**
- **Lines 10-13:** Extract form data
- **Lines 15-20:** Check all required fields
- **Lines 22-26:** Validate email format
- **Lines 28-33:** Validate phone number (8 digits)
- **Lines 35-40:** Validate guest count (positive number)

**Email Composition:**
- **Lines 42-53:** Prepare email content
  - Subject: "Catering Request from [Name]"
  - Body includes: name, email, phone, event type, date, guests, location, message
- **Lines 55-56:** Set email headers

**Email Sending:**
- **Lines 58-62:** Send email using PHP `mail()` function
- **Lines 64-70:** Set success/error message and redirect

**Current State:** Page shows email address for manual contact instead of automated form submission.

---

## Supporting Files

### 1. `includes/config.php`
**Purpose:** Configuration and session management
- Session initialization
- Site name constant: `SITE_NAME = 'LeckerHaus'`
- Session-based authentication check

### 2. `includes/db.php`
**Purpose:** Database connection
- MySQL connection using mysqli
- Database credentials
- Connection error handling

### 3. `includes/header.php`
**Purpose:** Common header with navigation
- Site branding
- Navigation menu (Home, Menu, Cart, Order History, Feedback, Catering)
- Login/Logout button based on session
- User greeting if logged in

### 4. `includes/footer.php`
**Purpose:** Common footer
- Contact information
- Email: support@leckerhaus.com
- Phone: (555) 123-4567
- Copyright notice

### 5. `js/validation.js`
**Purpose:** Client-side validation functions
- `validatePassword()`: 8+ chars, letter, number, special character
- `validatePhoneNumber()`: Exactly 8 digits
- `validateAddress()`: Minimum 10 characters
- Real-time form validation

### 6. CSS Files
- `css/base.css`: Common styles, header, footer, buttons, forms
- `css/menu.css`: Menu grid, search, filters, category tabs
- `css/cart.css`: Cart table, order summary, delivery form (grid 1.5fr 1fr)
- `css/order-history.css`: Order cards, status badges, button styling
- `css/order-confirmation.css`: Confirmation page, success icon
- `css/feedback.css`: Feedback form and display, star ratings
- `css/catering.css`: Package comparison table
- `css/login.css`: Login/register form styling
- `css/home.css`: Homepage hero section

### 7. `database/schema.sql`
**Purpose:** Database structure
- 6 tables: users, menu_items, cart_items, feedbacks, orders, order_items
- Relationships and foreign keys
- Indexes for performance

### 8. `database/seed_data.sql`
**Purpose:** Sample data for testing
- Sample menu items
- Test users

---

## Summary Table

| Requirement | Primary Files | Line References |
|------------|---------------|-----------------|
| **F1: Authentication** | login.php (1-103)<br>process-login.php (11-93: register, 95-134: login)<br>process-logout.php (1-8) | Form: 30-91<br>Register: 11-93<br>Login: 95-134<br>Logout: 5-6 |
| **F2: Menu & Ordering** | menu.php (8-162)<br>process-add-to-cart.php (30-65) | Query: 8-44<br>Display: 128-162<br>Add logic: 44-65 |
| **F3: Cart Management** | cart.php (54-142)<br>process-update-cart.php (35-42)<br>process-remove-from-cart.php (23-32) | Display: 54-94<br>Update: 35-42<br>Remove: 23-32 |
| **F4: Checkout** | cart.php (118-142)<br>process-checkout.php (15-112) | Form: 118-142<br>Validation: 15-40<br>Order: 72-105 |
| **F5: Status Tracking** | order-history.php (99-103)<br>process-advance-order.php (37-62) | Button: 99-103<br>Logic: 37-62 |
| **F6: Order History** | order-history.php (14-106) | Query: 14-22<br>Display: 59-106 |
| **F7: Feedback** | feedback.php (50-146)<br>process-feedback.php (16-42) | Form: 50-96<br>Display: 120-144<br>Submit: 38-42 |
| **F8: Catering** | catering.php (28-96) | Table: 32-86<br>Contact: 88-96 |
| **F9: Email** | process-catering.php (42-62) | Email: 42-62<br>(Not currently active) |

---

## Validation Rules Applied

| Field | Validation | Location | Line Reference |
|-------|------------|----------|----------------|
| **Username** | Min 3 characters | process-login.php | 26-29 |
| **Email** | Valid email format | process-login.php, process-checkout.php, process-feedback.php | 31-34, 22-26, 28-32 |
| **Password** | Min 8 chars + letter + number + special | process-login.php, validation.js | 36-44 |
| **Phone** | Exactly 8 digits | process-checkout.php, process-catering.php, validation.js | 35-40, 28-33 |
| **Address** | Min 10 characters | process-checkout.php, validation.js | 28-33 |
| **Rating** | 1-5 stars | process-feedback.php | 22-26 |
| **Guest Count** | Positive number | process-catering.php | 35-40 |

---

## Database Schema Reference

### Tables Used:
1. **users** - User accounts (id, username, full_name, email, password_hash, created_at)
2. **menu_items** - Food items (id, name, category, description, price, image_url)
3. **cart_items** - Shopping cart (id, user_id, menu_item_id, quantity, created_at)
4. **orders** - Order records (id, user_id, customer_name, customer_email, customer_phone, delivery_address, total_amount, status, created_at)
5. **order_items** - Order line items (id, order_id, menu_item_id, quantity, price)
6. **feedbacks** - Customer feedback (id, user_id, name, email, rating, order_number, feedback, approved, created_at)

---

## Session Variables Used

| Variable | Purpose | Set In | Used In |
|----------|---------|--------|---------|
| `$_SESSION['user_id']` | User ID | process-login.php (130) | All authenticated pages |
| `$_SESSION['username']` | Username | process-login.php (128) | header.php |
| `$_SESSION['email']` | User email | process-login.php (129) | - |
| `$_SESSION['login_error']` | Login error msg | process-login.php | login.php (8-10) |
| `$_SESSION['cart_success']` | Cart success msg | process-*-cart.php | cart.php (36-38) |
| `$_SESSION['cart_error']` | Cart error msg | process-*-cart.php | cart.php (39-41) |
| `$_SESSION['checkout_error']` | Checkout error | process-checkout.php | cart.php |
| `$_SESSION['feedback_success']` | Feedback success | process-feedback.php | feedback.php (19-21) |
| `$_SESSION['order_success']` | Order success | process-advance-order.php | order-history.php (30-32) |

---

**Document Created:** For IE4727 Assignment Documentation
**Last Updated:** Based on food-web-app-php folder structure
**Note:** All line numbers are approximate and based on current file versions. Line numbers may shift if files are modified.

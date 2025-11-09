# File Structure Comparison

## Traditional Folder vs No-AJAX Folder

### ✅ Main Pages (Root Level)

| Traditional (HTML) | No-AJAX (PHP) | Status |
|-------------------|---------------|--------|
| `index.html` | `index.php` | ✅ |
| `menu.html` | `menu.php` | ✅ |
| `cart.html` | `cart.php` | ✅ |
| `login.html` | `login.php` | ✅ (includes register) |
| `catering.html` | `catering.php` | ✅ |
| `feedback.html` | `feedback.php` | ✅ |
| `order-confirmation.html` | `order-confirmation.php` | ✅ |
| `order-history.php` | `order-history.php` | ✅ (same name) |

### Backend Files

**Traditional backend:**
- `process-login.php`
- `process-feedback.php`
- `api/add-to-cart.php` (returns JSON)
- `api/check-auth.php` (returns JSON)
- `api/get-cart.php` (returns JSON)
- `api/get-menu-items.php` (returns JSON)
- `api/place-order.php` (returns JSON)
- etc.

**No-AJAX backend:**
- `process-login.php` (handles both login & register)
- `process-logout.php`
- `process-add-to-cart.php` (redirects, no JSON)
- `process-update-cart.php` (redirects, no JSON)
- `process-remove-from-cart.php` (redirects, no JSON)
- `process-clear-cart.php` (redirects, no JSON)
- `process-checkout.php` (redirects, no JSON)
- `process-advance-order.php` (redirects, no JSON)
- `process-feedback.php` (redirects, no JSON)
- `process-catering.php` (redirects, no JSON)
- **NO API folder** (all responses via redirects)

### CSS Files

Both folders use the same CSS structure:
- `base.css` (global styles)
- `home.css`
- `login.css`
- `menu.css`
- `cart.css`
- `catering.css`
- `feedback.css`
- `order-confirmation.css`
- `order-history.css`

### JavaScript Files

**Traditional:** Multiple JS files with AJAX calls
- `auth.js` - Login/register with fetch()
- `validation.js` - Form validation

**No-AJAX:** Minimal JS only for validation
- `validation.js` - Client-side validation only (no AJAX)

## Key Architectural Differences

### 1. Authentication

**Traditional:**
- Separate login and register toggle in one HTML file
- Uses JavaScript to toggle between forms
- Submits to backend which processes and returns

**No-AJAX:**
- Same approach but uses URL parameter `?action=register`
- PHP renders correct form based on action
- Backend handles both login and register in same file

### 2. Data Flow

**Traditional:**
```
User → HTML → JavaScript → AJAX → API (JSON) → JavaScript → Update DOM
```

**No-AJAX:**
```
User → PHP Form → POST → Process PHP → Redirect → PHP Page with Data
```

### 3. Error Handling

**Traditional:**
```javascript
// Returns JSON response
{ "success": false, "error": "Invalid credentials" }
```

**No-AJAX:**
```php
// URL redirect with error parameter
header('Location: login.php?error=' . urlencode('Invalid credentials'));
```

### 4. Session Access

**Traditional:**
```javascript
// Fetch via AJAX
fetch('/backend/api/check-auth.php')
  .then(res => res.json())
  .then(data => { /* use data */ });
```

**No-AJAX:**
```php
<!-- Inline in header.php -->
<script>
window.SESSION_DATA = {
    isAuthenticated: <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>
};
</script>
```

## Summary

The no-AJAX folder successfully replicates all functionality of the traditional folder while:
- ✅ Using the same base file names (`.html` → `.php`)
- ✅ Avoiding all AJAX/JSON API calls
- ✅ Using form submissions and page reloads
- ✅ Passing session data via inline PHP
- ✅ Handling errors via URL parameters
- ✅ Maintaining the same visual styling

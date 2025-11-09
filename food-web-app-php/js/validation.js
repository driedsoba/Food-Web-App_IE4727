// Client-side form validation

// Password validation function
function validatePassword(password) {
  // At least 8 characters
  if (password.length < 8) {
    return 'Password must be at least 8 characters long';
  }

  // Must contain at least one letter
  if (!/[a-zA-Z]/.test(password)) {
    return 'Password must contain at least one letter';
  }

  // Must contain at least one number
  if (!/[0-9]/.test(password)) {
    return 'Password must contain at least one number';
  }

  // Must contain at least one special character
  if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
    return 'Password must contain at least one special character (!@#$%^&*(),.?":{}|<>)';
  }

  return null; // Valid password
}

// Phone number validation function (exactly 8 digits)
function validatePhoneNumber(phone) {
  const cleanPhone = phone.replace(/\s/g, '');
  if (!/^\d{8}$/.test(cleanPhone)) {
    return 'Phone number must be exactly 8 digits (e.g., 67489380)';
  }
  return null;
}

// Address validation function (minimum 10 characters)
function validateAddress(address) {
  if (address.trim().length < 10) {
    return 'Address must be at least 10 characters';
  }
  return null;
}

document.addEventListener('DOMContentLoaded', function () {
  const authForm = document.getElementById('authForm');

  if (authForm) {
    authForm.addEventListener('submit', function (e) {
      const errorMessage = document.getElementById('errorMessage');
      const action = document.getElementById('formAction').value;

      errorMessage.style.display = 'none';

      if (action === 'register') {
        // Register validation
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        // Validate password strength
        const passwordError = validatePassword(password);
        if (passwordError) {
          e.preventDefault();
          errorMessage.textContent = passwordError;
          errorMessage.style.display = 'block';
          return false;
        }

        if (password !== confirmPassword) {
          e.preventDefault();
          errorMessage.textContent = 'Passwords do not match';
          errorMessage.style.display = 'block';
          return false;
        }
      }
    });
  }

  // Checkout form validation
  const checkoutForm = document.querySelector('form[action*="process-checkout"]');
  if (checkoutForm) {
    checkoutForm.addEventListener('submit', function (e) {
      const address = document.querySelector('input[name="delivery_address"]').value;
      const phone = document.querySelector('input[name="customer_phone"]').value;

      // Validate address
      const addressError = validateAddress(address);
      if (addressError) {
        e.preventDefault();
        alert(addressError);
        return false;
      }

      // Validate phone
      const phoneError = validatePhoneNumber(phone);
      if (phoneError) {
        e.preventDefault();
        alert(phoneError);
        return false;
      }
    });
  }

  // Catering form validation
  const cateringForm = document.querySelector('form[action*="process-catering"]');
  if (cateringForm) {
    cateringForm.addEventListener('submit', function (e) {
      const phone = document.querySelector('input[name="phone"]').value;
      const guestCount = document.querySelector('input[name="guest_count"]').value;

      // Validate phone
      const phoneError = validatePhoneNumber(phone);
      if (phoneError) {
        e.preventDefault();
        alert(phoneError);
        return false;
      }

      // Validate guest count
      if (!guestCount || isNaN(guestCount) || guestCount <= 0) {
        e.preventDefault();
        alert('Guest count must be a positive number');
        return false;
      }
    });
  }
});

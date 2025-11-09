// Client-side form validation (no AJAX)

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

        if (password !== confirmPassword) {
          e.preventDefault();
          errorMessage.textContent = 'Passwords do not match';
          errorMessage.style.display = 'block';
          return false;
        }

        if (password.length < 6) {
          e.preventDefault();
          errorMessage.textContent = 'Password must be at least 6 characters long';
          errorMessage.style.display = 'block';
          return false;
        }
      }
    });
  }
});

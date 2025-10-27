// formvalidation.js - Custom validation for jobs.html form
// Prevents form submission if custom rules fail; shows error messages below fields

document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('.job-form'); // Target class="job-form"
  if (!form) return; // Exit if form not found

  form.addEventListener('submit', function (event) {
    event.preventDefault(); // Stop default submit to run custom checks

    clearErrors(); // Remove any previous error messages

    // Get field values (trim to remove extra spaces)
    const name = document.getElementById('CustName').value.trim();
    const email = document.getElementById('CustEmail').value.trim();
    const startDate = document.getElementById('startdate').value;
    const experience = document.getElementById('comments').value.trim();

    let isValid = true; // Flag to track if all checks pass

    // 1. Name: Only letters and spaces
    const nameRegex = /^[a-zA-Z\s]+$/;
    if (!nameRegex.test(name)) {
      showError('CustName', 'Name must contain only letters and spaces.');
      isValid = false;
    }

    // 2-3. Email: Username @ domain with rules
    // Username: letters, numbers, hyphens, periods
    // Domain: 2-4 parts separated by ., last part 2-3 letters
    const emailRegex = /^[a-zA-Z0-9.-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$/;
    if (!emailRegex.test(email)) {
      showError(
        'CustEmail',
        'Email must have username@domain. Domain needs 2-4 parts, last part 2-3 letters.'
      );
      isValid = false;
    }

    // 4. Start date: Must be future
    if (startDate) {
      const today = new Date();
      today.setHours(0, 0, 0, 0); // Set to start of today
      const selected = new Date(startDate);
      if (selected <= today) {
        showError('startdate', 'Start date must be in the future.');
        isValid = false;
      }
    }

    // 5. Experience: Cannot be empty
    if (!experience) {
      showError('comments', 'Experience field cannot be empty.');
      isValid = false;
    }

    // Extra validations
    // - Name at least 2 characters
    if (name.length < 2) {
      showError('CustName', 'Name must be at least 2 characters.');
      isValid = false;
    }
    // - Email no consecutive dots/hyphens in username
    if (email.includes('..') || email.includes('--')) {
      showError('CustEmail', 'Email username cannot have .. or --.');
      isValid = false;
    }
    // - Experience at least 10 characters
    if (experience.length < 10) {
      showError('comments', 'Experience must be at least 10 characters.');
      isValid = false;
    }

    // If all valid, allow form to submit normally
    if (isValid) {
      form.submit();
    }
  });

  // Function to show error message below a field
  function showError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    errorDiv.style.color = 'red';
    errorDiv.style.fontSize = '12px';
    errorDiv.style.marginTop = '4px';
    field.parentNode.appendChild(errorDiv); // To add error message below the field
  }

  // Function to clear all error messages
  function clearErrors() {
    const errors = document.querySelectorAll('.error-message');
    errors.forEach(error => error.remove());
  }
});

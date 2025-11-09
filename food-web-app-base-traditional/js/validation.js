// Validate email format
function isValidEmail(email) {
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Validate phone number
function isValidPhone(phone) {
    var re = /^[\d\s\+\-\(\)]+$/;
    return re.test(phone) && phone.replace(/\D/g, '').length >= 8;
}

// Validate checkout form
function validateCheckoutForm(form) {
    var name = form.customer_name.value.trim();
    var email = form.customer_email.value.trim();
    var phone = form.customer_phone.value.trim();
    var address = form.delivery_address.value.trim();
    
    if (name === '') {
        alert('Please enter your name');
        form.customer_name.focus();
        return false;
    }
    
    if (name.length < 2) {
        alert('Name must be at least 2 characters');
        form.customer_name.focus();
        return false;
    }
    
    if (email === '') {
        alert('Please enter your email');
        form.customer_email.focus();
        return false;
    }
    
    if (!isValidEmail(email)) {
        alert('Please enter a valid email address');
        form.customer_email.focus();
        return false;
    }
    
    if (phone === '') {
        alert('Please enter your phone number');
        form.customer_phone.focus();
        return false;
    }
    
    if (!isValidPhone(phone)) {
        alert('Please enter a valid phone number');
        form.customer_phone.focus();
        return false;
    }
    
    if (address === '') {
        alert('Please enter your delivery address');
        form.delivery_address.focus();
        return false;
    }
    
    if (address.length < 10) {
        alert('Please provide a complete delivery address');
        form.delivery_address.focus();
        return false;
    }
    
    return true;
}

// Validate login form
function validateLoginForm(form) {
    var email = form.email.value.trim();
    var password = form.password.value;
    
    if (email === '') {
        alert('Please enter your email');
        form.email.focus();
        return false;
    }
    
    if (!isValidEmail(email)) {
        alert('Please enter a valid email address');
        form.email.focus();
        return false;
    }
    
    if (password === '') {
        alert('Please enter your password');
        form.password.focus();
        return false;
    }
    
    return true;
}

// Validate registration form
function validateRegisterForm(form) {
    var username = form.username.value.trim();
    var email = form.email.value.trim();
    var password = form.password.value;
    var confirmPassword = form.confirm_password.value;
    var fullName = form.full_name.value.trim();
    
    if (username === '') {
        alert('Please enter a username');
        form.username.focus();
        return false;
    }
    
    if (username.length < 3) {
        alert('Username must be at least 3 characters');
        form.username.focus();
        return false;
    }
    
    if (email === '') {
        alert('Please enter your email');
        form.email.focus();
        return false;
    }
    
    if (!isValidEmail(email)) {
        alert('Please enter a valid email address');
        form.email.focus();
        return false;
    }
    
    if (password === '') {
        alert('Please enter a password');
        form.password.focus();
        return false;
    }
    
    if (password.length < 6) {
        alert('Password must be at least 6 characters');
        form.password.focus();
        return false;
    }
    
    if (confirmPassword === '') {
        alert('Please confirm your password');
        form.confirm_password.focus();
        return false;
    }
    
    if (password !== confirmPassword) {
        alert('Passwords do not match');
        form.confirm_password.focus();
        return false;
    }
    
    if (fullName === '') {
        alert('Please enter your full name');
        form.full_name.focus();
        return false;
    }
    
    return true;
}

// Validate feedback form
function validateFeedbackForm(form) {
    var name = form.customer_name.value.trim();
    var rating = form.rating.value;
    var comment = form.comment.value.trim();
    
    if (name === '') {
        alert('Please enter your name');
        form.customer_name.focus();
        return false;
    }
    
    if (rating === '' || rating < 1 || rating > 5) {
        alert('Please select a rating (1-5)');
        form.rating.focus();
        return false;
    }
    
    if (comment === '') {
        alert('Please enter your feedback');
        form.comment.focus();
        return false;
    }
    
    if (comment.length < 10) {
        alert('Feedback must be at least 10 characters');
        form.comment.focus();
        return false;
    }
    
    return true;
}

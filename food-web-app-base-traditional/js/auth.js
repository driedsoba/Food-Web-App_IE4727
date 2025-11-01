// Simple auth state management
(function() {
    // Check if user is logged in
    fetch('backend/api/check-auth.php')
        .then(response => response.json())
        .then(data => {
            if (data.isAuthenticated) {
                // Show user greeting and logout button
                const userGreeting = document.querySelector('.user-greeting');
                const logoutButton = document.querySelector('.logout-button');
                const loginButton = document.querySelector('.login-button');

                if (userGreeting) {
                    userGreeting.textContent = `Hello, ${data.user.username}!`;
                    userGreeting.style.display = 'inline';
                }

                if (logoutButton) {
                    logoutButton.style.display = 'inline-flex';
                    logoutButton.onclick = function() {
                        window.location.href = 'backend/process-login.php?action=logout';
                    };
                }

                if (loginButton) {
                    loginButton.style.display = 'none';
                }

                // Store in sessionStorage for client-side checks
                sessionStorage.setItem('user', JSON.stringify(data.user));
            } else {
                // Clear sessionStorage
                sessionStorage.removeItem('user');
            }
        })
        .catch(error => {
            console.error('Auth check failed:', error);
        });
})();

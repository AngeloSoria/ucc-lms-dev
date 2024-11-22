document.addEventListener('DOMContentLoaded', function () {
    const usernameField = document.querySelector('input[name="username"]');
    const passwordField = document.querySelector('input[name="password"]');
    const rememberMeCheckbox = document.querySelector('input[name="remember_me"]');
    const loginForm = document.querySelector('.form-login');

    // Function to get a cookie value by name
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    // Check if there are saved values in cookies
    const savedUsername = getCookie('savedUsername');
    const savedPassword = getCookie('savedPassword');

    if (savedUsername) {
        usernameField.value = savedUsername;
        rememberMeCheckbox.checked = true; // Automatically check if username is found
    }
    if (savedPassword) {
        passwordField.value = savedPassword;
    }

    // Handle form submission
    // loginForm.addEventListener('submit', function(event) {
    //     event.preventDefault(); // Prevent the default form submission

    //     const formData = new FormData(loginForm);

    //     // Make an AJAX request to the server
    //     fetch('src/controllers/LoginProcessor.php', { // Update this path
    //         method: 'POST',
    //         body: formData
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         if (data.success) {
    //             // Save cookies if login is successful
    //             if (rememberMeCheckbox.checked) {
    //                 document.cookie = `savedUsername=${usernameField.value}; max-age=${30 * 24 * 60 * 60}; path=/`;
    //                 document.cookie = `savedPassword=${passwordField.value}; max-age=${30 * 24 * 60 * 60}; path=/`;
    //             } else {
    //                 // Clear cookies if "Remember Me" is not checked
    //                 document.cookie = 'savedUsername=; max-age=0; path=/';
    //                 document.cookie = 'savedPassword=; max-age=0; path=/';
    //             }
    //             // Redirect or update UI as needed
    //             window.location.href = '/dashboard.php'; // TODO: UPDATE MO TO PRE
    //         } else {
    //             // Show an error message
    //             alert('Invalid username or password.');
    //         }
    //     })
    //     .catch(error => {
    //         console.error('Error:', error);
    //     });
    // });
});

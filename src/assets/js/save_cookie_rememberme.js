document.addEventListener("DOMContentLoaded", function () {
    const usernameField = document.getElementById("username");
    const passwordField = document.getElementById("password");
    const rememberMeCheckbox = document.getElementById("rememberMe");

    // Load credentials if "Remember Me" was previously checked
    if (localStorage.getItem("rememberMe") === "true") {
        usernameField.value = localStorage.getItem("username") || "";
        passwordField.value = localStorage.getItem("password") || "";
        rememberMeCheckbox.checked = true;
    }

    // Form submission logic
    const loginForm = document.querySelector("#modal_LoginForm form");
    loginForm.addEventListener("submit", function (event) {
        const username = usernameField.value;
        const password = passwordField.value;
        const rememberMe = rememberMeCheckbox.checked;

        if (rememberMe) {
            // Save credentials in localStorage
            localStorage.setItem("username", username);
            localStorage.setItem("password", password);
            localStorage.setItem("rememberMe", "true");
        } else {
            // Clear stored credentials if "Remember Me" is unchecked
            localStorage.removeItem("username");
            localStorage.removeItem("password");
            localStorage.removeItem("rememberMe");
        }

        // You can uncomment the following line to proceed with form submission
        // loginForm.submit();
        alert("Form submitted!");  // Placeholder
    });
});

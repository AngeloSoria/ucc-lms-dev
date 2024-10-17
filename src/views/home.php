<?php
session_start();
include_once '../config/connection.php';
include_once '../config/rootpath.php';
include '../controllers/LoginController.php';

$loginController = new LoginController();
$_isInvalidCredentials = false; // Variable to check if login failed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']);

    // Attempt to log in
    $loginResult = $loginController->login($username, $password);

    if ($loginResult === true) {
        exit();
    } else {
        // Set invalid credentials result for the view
        $_isInvalidCredentials = true;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<?php include "partials/home_header.php"; ?>

<body data-bs-theme="light">
    <!-- Navbar -->
    <?php include "partials/public/home_navbar.php"; ?>

    <section class="min-vh-50">
        <!-- Carousel -->
        <div
            id="carouselExampleFade"
            class="carousel slide carousel-fade"
            data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button
                    type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="0"
                    class="active"
                    aria-current="true"
                    aria-label="Slide 1"></button>
                <button
                    type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button
                    type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img
                        src="src/assets/images/placeholder-1.jpg"
                        class="d-block w-100"
                        alt="..." />
                </div>
                <div class="carousel-item">
                    <img
                        src="src/assets/images/placeholder-2.jpg"
                        class="d-block w-100"
                        alt="..." />
                </div>
                <div class="carousel-item">
                    <img
                        src="src/assets/images/placeholder-3.jpg"
                        class="d-block w-100"
                        alt="..." />
                </div>
            </div>
            <button
                class="carousel-control-prev"
                type="button"
                data-bs-target="#carouselExampleFade"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button
                class="carousel-control-next"
                type="button"
                data-bs-target="#carouselExampleFade"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

    </section>

    <!-- LOGIN FORM (Popup) -->
    <?php include "partials/public/modal_formLogin.php" ?>

    <?php include "partials/home_footer.php"; ?>
    <!-- <script src="src/assets/js/login.js"></script> -->
    <script src="src/assets/js/landingPage_Manager.js"></script>

    <?php if ($_isInvalidCredentials) { ?>
        <script>
            // Initialize the modal only once
            var loginModalElement = document.getElementById('modal_LoginForm');
            var loginModal = new bootstrap.Modal(loginModalElement);

            // Show the modal because of invalid credentials
            loginModal.show();

            // Function to hide the invalid feedback message
            function hideInvalidFeedback() {
                var invalidFeedback = document.querySelector('#invalid-feedback');
                if (invalidFeedback && invalidFeedback.classList.contains('d-block')) {
                    invalidFeedback.classList.remove('d-block');
                }
            }

            // Hide feedback on input focus
            var usernameInput = document.getElementById('userid');
            var passwordInput = document.getElementById('password');

            // Add event listeners to hide the feedback on focus
            if (usernameInput) {
                usernameInput.addEventListener('focus', hideInvalidFeedback);
            }
            if (passwordInput) {
                passwordInput.addEventListener('focus', hideInvalidFeedback);
            }

            // Hide feedback when modal is closed
            loginModalElement.addEventListener('hidden.bs.modal', hideInvalidFeedback);
        </script>
    <?php } ?>
</body>
<script src="src/assets/js/home-main.js"></script>

</html>
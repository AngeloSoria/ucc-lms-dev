<?php
session_start();

require_once(__DIR__ . '../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['Login']);

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
        // Redirect to the user dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        // Set invalid credentials result for the view
        $_isInvalidCredentials = true;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<?php include "partials/public/home_header.php"; ?>
<body>

    <!-- Navbar -->
    <?php include "partials/public/home_navbar.php" ?>

    <!-- CONTENT -->

    <section class="p-5 content-100vh">
        <h3>Frequently Asked Question (FAQ)</h3>
        <hr>
        <!-- Accordion -->
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Accordion Item #1
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Accordion Item #2
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Accordion Item #3
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- END OF CONTENT -->

    <!-- LOGIN FORM (Popup) -->
    <?php include "partials/public/modal_formLogin.php" ?>

    <?php include "partials/public/home_footer.php"; ?>

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
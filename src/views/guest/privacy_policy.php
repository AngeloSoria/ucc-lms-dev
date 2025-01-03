<?php
session_start();

require_once(__DIR__ . '../../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Controllers']['Login']);

// Fetch selected carousel items (up to 4) for display on the homepage
$database = new Database();
$pdo = $database->getConnection();


$loginController = new LoginController();
$_loginResult = isset($_SESSION['_loginResult']) ? $_SESSION['_loginResult'] : null; // Variable to check if login failed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rememberMe = isset($_POST['remember_me']);

    $_SESSION['_loginResult'] = $loginController->login(); // Validate

    $_loginResult = $_SESSION['_loginResult'];
    unset($_SESSION['_loginResult']);
}

// Will check if theres a session, so that a logged in users cant view this guest page.
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    // Redirect to the dashboard
    header('Location: ' . 'src/views/users/' . strtolower($_SESSION['role']) . '/dashboard_' . strtolower($_SESSION['role']) . '.php');
}
?>


<!DOCTYPE html>
<html lang="en">
<?php require_once "../partials/public/home_header.php"; ?>

<body>

    <!-- Navbar -->
    <?php require_once "../partials/public/home_navbar.php"; ?>

    <!-- CONTENT -->

    <section class="p-5 min-vh-100">
        <h3>Privacy Policy</h3>
        <hr>
        <!-- Accordion -->

    </section>


    <!-- END OF CONTENT -->

    <!-- LOGIN FORM (Popup) -->
    <?php include "../partials/public/modal_formLogin.php"; ?>

    <?php include "../partials/public/home_footer.php"; ?>
</body>
<?php
if ($_loginResult === false) { ?>
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
<script src="<?php echo asset('js/landingPage_Manager.js') ?>"></script>
<script src="<?php echo asset('js/home-main.js') ?>"></script>
<script src="<?php echo asset('js/root.js') ?>"></script>

</html>
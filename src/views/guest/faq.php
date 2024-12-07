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
        <h3>Frequently Asked Question (FAQ)</h3>
        <hr>
        <!-- Accordion -->
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Student Help: Account Login Instructions
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>If you don't know your login credentials, follow the steps below to access your accounts:</strong>
                        <br>
                        Login Credentials Format:
                        <br>
                        <br>
                        <p>Username: First letter of your first name + Last name + period + user ID</p>
                        <ul>
                            <li>Example: <i>JDoe.1004</i> (for a student named John Doe with a user ID of 1004)</li>
                        </ul>

                        <p>Password: Last name + birthdate in MMDDYYYY format</p>
                        <ul>
                            <li>Example: Doe09242002 (for John Doe with a birthdate of September 24, 2002)</li>
                        </ul>

                        <br>
                        <h6>Important Notes:</h6>
                        <ul>
                            <li>Case Sensitivity: Passwords are case-sensitive, so ensure that the first letter of your last name is uppercase.</li>
                            <li>Where to Use: Use this format to log in to your eLearning Management System (eLMS), Microsoft Office 365, and other related accounts.</li>
                        </ul>

                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        How to Get Your Account Information:
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>Since no email is used for registration and only admins create accounts, here's how you can obtain your login credentials:</strong>
                        <ol>
                            <li>Physical Distribution:
                                After your account is created, the admin will provide your username and password either on a physical form or during an in-person session. Ensure you keep this information safe.</li>
                            <li>Contact Admin or IT for Assistance:
                                If you don't know your credentials or if you need help accessing your account, please reach out to the admin or IT support at your campus. They will provide you with your login details.</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Account Reset Option:
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>If you need to reset your password:</strong>
                        <ul>
                            <li>Contact Admin or IT Support:
                                To reset your password, you will need to contact the admin or IT support at your campus. They can verify your identity and assist you with resetting your account password.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
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
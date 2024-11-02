<?php
session_start();

require_once(__DIR__ . '../../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Controllers']['Login']);
require_once(FILE_PATHS['Controllers']['Carousel']);


$loginController = new LoginController();
$carouselController = new CarouselController(); // Create an instance of the CarouselController
$_isInvalidCredentials = false; // Variable to check if login failed

// Fetch all carousel items
$database = new Database();
$pdo = $database->getConnection();

$stmt = $pdo->query("SELECT carousel_id, title, image, view_type FROM carousel WHERE view_type = 'home'");
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

<?php include "../partials/public/home_header.php"; ?>

<body data-bs-theme="light">
    <!-- Navbar -->
    <?php include "../partials/public/home_navbar.php"; ?>

    <section class="min-vh-50">
        <!-- Carousel -->
        <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php foreach ($images as $index => $item): ?>
                    <button type="button" data-bs-target="#carouselExampleFade" data-bs-slide-to="<?= $index ?>"
                        class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                        aria-label="Slide <?= $index + 1 ?>"></button>
                <?php endforeach; ?>
            </div>
            <div class="carousel-inner">
                <?php if (!empty($images)): ?>
                    <?php foreach ($images as $index => $item): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="data:image/jpeg;base64,<?= base64_encode($item['image']); ?>" class="d-block w-100" alt="<?= htmlspecialchars($item['title']); ?>" />
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carousel-item active">
                        <img src="path/to/default/image.jpg" class="d-block w-100" alt="No images available" />
                    </div>
                <?php endif; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>


    <!-- LOGIN FORM (Popup) -->
    <?php include "../partials/public/modal_formLogin.php"; ?>

    <?php include "../partials/public/home_footer.php"; ?>
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
<script src="src/assets/js/root.js"></script>

</html>
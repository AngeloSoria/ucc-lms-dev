<?php
session_start();

require_once(__DIR__ . '../../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Controllers']['Login']);
require_once(FILE_PATHS['Controllers']['Carousel']);

// Fetch selected carousel items (up to 4) for display on the homepage
$database = new Database();
$pdo = $database->getConnection();


$loginController = new LoginController();
$carouselController = new CarouselController($pdo); // Create an instance of the CarouselController

$_loginResult = isset($_SESSION['_loginResult']) ? $_SESSION['_loginResult'] : null; // Variable to check if login failed


// CAROUSEL
$stmt = $pdo->query("SELECT carousel_id, title, image_path, view_type FROM carousel WHERE view_type = 'home' AND is_selected = 1 ORDER BY created_at DESC LIMIT 4");
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rememberMe = isset($_POST['remember_me']);

    $_SESSION['_loginResult'] = $loginController->login(); // Validate

    $_loginResult = $_SESSION['_loginResult'];
    unset($_SESSION['_loginResult']);
}
// =================================

// Will check if theres a session, so that a logged in users cant view this guest page.
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    // Redirect to the dashboard
    header('Location: ' . 'src/views/users/' . strtolower(str_replace(" ", "_", $_SESSION['role'])) . '/dashboard_' . strtolower(str_replace(" ", "_", $_SESSION['role'])) . '.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<?php require_once "../partials/public/home_header.php"; ?>

<body>
    <!-- Navbar -->
    <?php require_once "../partials/public/home_navbar.php"; ?>

    <section class="min-vh-50">
        <!-- Carousel -->
        <div id="homeCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php if (!empty($images)): ?>
                    <?php foreach ($images as $index => $single_user): ?>
                        <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="<?= $index ?>"
                            class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                            aria-label="Slide <?= $index + 1 ?>">
                        </button>
                    <?php endforeach; ?>
                <?php else: ?>
                    <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="1" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="2" class="active" aria-current="true" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="1" class="active" aria-current="true" aria-label="Slide 3"></button>
                <?php endif; ?>
            </div>
            <div class="carousel-inner">
                <?php if (!empty($images)): ?>
                    <?php foreach ($images as $index => $single_user): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="<?= BASE_PATH_LINK . htmlspecialchars($single_user['image_path']); ?>"
                                class="d-block w-100" alt="<?= htmlspecialchars($single_user['title']); ?>" />
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carousel-item active">
                        <img src="<?php echo asset('img/placeholder-1.jpg') ?>" class="d-block w-100" alt="No images available" />
                    </div>
                    <div class="carousel-item">
                        <img src="<?php echo asset('img/placeholder-2.jpg') ?>" class="d-block w-100" alt="No images available" />
                    </div>
                    <div class="carousel-item">
                        <img src="<?php echo asset('img/placeholder-3.jpg') ?>" class="d-block w-100" alt="No images available" />
                    </div>
                <?php endif; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- LOGIN FORM (Popup) -->
    <?php require_once "../partials/public/modal_formLogin.php"; ?>

    <?php require_once "../partials/public/home_footer.php"; ?>
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
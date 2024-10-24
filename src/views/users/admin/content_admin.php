<?php
require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['Carousel']);

$CURRENT_PAGE = "content";
session_start(); // Start the session at the top of your file

// Generate a CSRF token if one doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$database = new Database();
$db = $database->getConnection(); // Establish the database connection

$carouselController = new CarouselController($db); // Create an instance of the CarouselController

// At the beginning of your main file
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form inputs
    $carouselData = [
        'title' => htmlspecialchars(strip_tags($_POST["title"])),
        'view_type' => htmlspecialchars(strip_tags($_POST['view_type'])),
        'file' => $_FILES['file'] // Handle the image upload
    ];


    // You can create a method in your CarouselController to handle the insert
    $addCarouselResult = $carouselController->addCarouselItem($carouselData);

    // Optional: Store the result in a session variable or handle success/failure
    if ($addCarouselResult) {
        // Success, maybe redirect or show a success message
        $_SESSION['message'] = "Carousel item added successfully.";
    } else {
        // Handle error
        $_SESSION['message'] = "Failed to add carousel item.";
    }

    // Redirect to the same page or handle as needed
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

try {
    // Fetch images from the database where view_type is 'home'
    $stmt = $db->query("SELECT carousel_id, title, image, view_type FROM carousel WHERE view_type = 'home'");
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Database query failed: ' . htmlspecialchars($e->getMessage()) . '</div>';
    $images = []; // Set to empty if query fails
}

// Optional: Fetch all carousel items to display them
$carouselItems = $carouselController->getAllCarouselItems(); // Fetch all carousel items

?>

<!DOCTYPE html>
<html lang="en">
<?php require_once(FILE_PATHS['Partials']['User']['Head']) ?>

<body class="">
    <?php require_once(FILE_PATHS['Partials']['User']['Navbar']) ?>

    <section class="d-flex justify-content-between gap-2 box-sizing-border-box m-0 p-0">
        <!-- SIDEBAR -->
        <?php require_once(FILE_PATHS['Partials']['User']['Sidebar']) ?>

        <!-- content here -->
        <section class="row min-vh-100 w-100 m-0 p-2 d-flex justify-content-end align-items-start" id="contentSection">
            <div class="col box-sizing-border-box flex-grow-1 bg-white border rounded p-2 pt-3">

                <div>
                    <ul class="nav nav-tabs" id="contentTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Home</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="user-content-tab" data-bs-toggle="tab" data-bs-target="#user-content" type="button" role="tab" aria-controls="user-content" aria-selected="false">User Content</button>
                        </li>
                    </ul>

                    <section class="tab-content border p-2 mt-2" id="contentTabContent">
                        <!-- Home Tab Section -->
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h4 class="mb-3">Homepage Carousel <i class="text-danger" style="font-size: 0.85rem; font-weight: normal;">(Max item: 4)</i></h4>
                            <?php require_once(FILE_PATHS['Partials']['HighLevel']['Sortable']['Carousel']['Home']) ?>
                        </div>

                        <!-- User Content Tab Section -->
                        <div class="tab-pane fade" id="user-content" role="tabpanel" aria-labelledby="user-content-tab">
                            <h4 class="mb-3">User Content Section</h4>
                            <p>This is the User Content section. You can display relevant content for users here.</p>
                        </div>
                    </section>
                </div>

            </div>
            <div class="col bg-transparent d-flex flex-column justify-content-start align-items-center gap-2 px-1 box-sizing-border-box" id="widgetPanel">
                <!-- CALENDAR -->
                <?php require_once(FILE_PATHS['Partials']['User']['Calendar']) ?>

                <!-- TASKS -->
                <?php require_once(FILE_PATHS['Partials']['User']['Tasks']) ?>
            </div>
        </section>

    </section>

    <!-- FOOTER -->
    <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
</body>
<script src="../../../../src/assets/js/admin-main.js"></script>

</html>
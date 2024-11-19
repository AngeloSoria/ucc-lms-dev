<?php
session_start();
$CURRENT_PAGE = "content";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['Carousel']);
require_once(FILE_PATHS['Functions']['ToastLogger']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
checkUserAccess(['Admin']);

// Generate a CSRF token if one doesn't exist
// if (empty($_SESSION['csrf_token'])) {
//     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
// }

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
    $carouselController->addCarouselItem();
}

try {
    // Fetch selected and unselected images for homepage carousel (view_type = 'home')
    $stmtSelectedHome = $db->query("SELECT carousel_id, title, image_path, view_type FROM carousel WHERE view_type = 'home' AND is_selected = 1 ORDER BY created_at DESC LIMIT 4");
    $selectedImagesHome = $stmtSelectedHome->fetchAll(PDO::FETCH_ASSOC);

    $stmtUnselectedHome = $db->query("SELECT carousel_id, title, image_path, view_type FROM carousel WHERE view_type = 'home' AND is_selected = 0 ORDER BY created_at DESC");
    $unselectedImagesHome = $stmtUnselectedHome->fetchAll(PDO::FETCH_ASSOC);

    // Fetch selected and unselected images for user content section (view_type = 'dashboard')
    $stmtSelectedDashboard = $db->query("SELECT carousel_id, title, image_path, view_type FROM carousel WHERE view_type = 'dashboard' AND is_selected = 1 ORDER BY created_at DESC LIMIT 4");
    $selectedImagesDashboard = $stmtSelectedDashboard->fetchAll(PDO::FETCH_ASSOC);

    $stmtUnselectedDashboard = $db->query("SELECT carousel_id, title, image_path, view_type FROM carousel WHERE view_type = 'dashboard' AND is_selected = 0 ORDER BY created_at DESC");
    $unselectedImagesDashboard = $stmtUnselectedDashboard->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Database query failed: ' . htmlspecialchars($e->getMessage()) . '</div>';
    $selectedImagesHome = $unselectedImagesHome = [];
    $selectedImagesDashboard = $unselectedImagesDashboard = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once(FILE_PATHS['Partials']['User']['Head']) ?>

<body data-theme="light">
    <div class="wrapper shadow-sm border">
        <?php require_once(FILE_PATHS['Partials']['User']['Navbar']) ?>

        <section class="d-flex justify-content-between gap-2 box-sizing-border-box m-0 p-0">
            <!-- SIDEBAR -->
            <?php require_once(FILE_PATHS['Partials']['User']['Sidebar']) ?>

            <!-- content here -->
            <section id="contentSection">
                <div class="col box-sizing-border-box flex-grow-1 bg-white border rounded p-2 pt-3">
                    <div>
                        <div class="d-flex justify-content-between px-2">
                            <h5 class="mb-3">Carousel Management</h5>
                            <div>
                                <button class="btn btn-primary c-primary" data-bs-toggle="modal"
                                    data-bs-target="#fileSelectModal">
                                    <i class="bi bi-plus-circle"></i>
                                    Add New
                                </button>
                            </div>
                        </div>
                        <ul class="nav nav-tabs" id="contentTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                    type="button" role="tab" aria-controls="home" aria-selected="true">Home</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="user-content-tab" data-bs-toggle="tab"
                                    data-bs-target="#user-content" type="button" role="tab" aria-controls="user-content"
                                    aria-selected="false">Dashboard</button>
                            </li>
                        </ul>

                        <section class="tab-content border p-2 mt-2" id="contentTabContent">
                            <!-- Home Carousel Section -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <!-- Display Selected Images for Homepage -->
                                <br>
                                <h5>Home Carousel <i class="text-danger"
                                        style="font-size: 0.85rem; font-weight: normal;">(Max item: 4)</i></h5>
                                <?php
                                $images = $selectedImagesHome;
                                require(FILE_PATHS['Partials']['HighLevel']['Dragger']["Carousel"]["Home"]);
                                ?>

                            </div>

                            <!-- User Content Tab Section -->
                            <div class="tab-pane fade" id="user-content" role="tabpanel" aria-labelledby="user-content-tab">
                                <h5>Dashboard Carousel <i class="text-danger"
                                        style="font-size: 0.85rem; font-weight: normal;">(Max item: 4)</i></h5>
                                <?php
                                $images = $selectedImagesDashboard;
                                require(FILE_PATHS['Partials']['HighLevel']['Dragger']["Carousel"]["Home"]);
                                ?>
                            </div>
                        </section>
                    </div>
                </div>
            </section>
        </section>

        <!-- FOOTER -->
        <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
    </div>
</body>
<script src="<?php asset('js/admin-main.js') ?>"></script>
<?php
// Show Toast
if (isset($_SESSION["_ResultMessage"]) && $_SESSION["_ResultMessage"] != null) {
    $type = $_SESSION["_ResultMessage"][0];
    $text = $_SESSION["_ResultMessage"][1];
    makeToast([
        'type' => $type,
        'message' => $text,
    ]);
    outputToasts(); // Execute toast on screen.
    unset($_SESSION["_ResultMessage"]); // Dispose
}

?>

</html>
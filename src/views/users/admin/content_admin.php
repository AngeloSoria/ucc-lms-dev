<?php
session_start();
$CURRENT_PAGE = "content";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['Carousel']);
require_once(FILE_PATHS['Functions']['ToastLogger']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
require(FILE_PATHS['Partials']['HighLevel']['Dragger']["Carousel"]["Sortable"]);
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
    <link rel="stylesheet" href="<?php echo asset("css/sortable-main.css") ?>">
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

                        <section class="tab-content border p-2" id="contentTabContent">
                            <!-- Home Carousel Section -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <!-- Display Selected Images for Homepage -->
                                <br>
                                <h5>
                                    Home Carousel
                                    <p class="text-danger fs-7 p-2">
                                        <i class="bi bi-info-circle-fill"></i>
                                        (Max item: 4) / Any new uploaded content will overwrite the last added.
                                    </p>
                                </h5>
                                <div id="sortableCarousel" class="sortable-main border bg-white">
                                    <div class="d-flex flex-wrap gap-0 p-1 bg-light" role="listbox" id="sortableContentHomeCarousel">
                                        <?php if (!empty($selectedImagesHome)): ?>
                                            <?php createSortable($selectedImagesHome); ?>
                                        <?php else: ?>
                                            <h6 class="d-block text-center w-100 p-2">No uploaded content yet.</h6>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div>

                            <!-- User Content Tab Section -->
                            <div class="tab-pane fade" id="user-content" role="tabpanel" aria-labelledby="user-content-tab">
                                <h5>
                                    Dashboard Carousel
                                    <p class="text-danger fs-7 p-2">
                                        <i class="bi bi-info-circle-fill"></i>
                                        (Max item: 4) / Any new uploaded content will overwrite the last added.
                                    </p>
                                </h5>
                                <div id="sortableCarousel" class="sortable-main border bg-white">
                                    <div class="d-flex flex-wrap gap-0 p-1 bg-light" role="listbox" id="sortableContentHomeCarousel">
                                        <?php if (!empty($selectedImagesDashboard)): ?>
                                            <?php createSortable($selectedImagesDashboard); ?>
                                        <?php else: ?>
                                            <h6 class="d-block text-center w-100 p-2">No uploaded content yet.</h6>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </section>
        </section>
        <!-- MODAL -->
        <?php require_once(FILE_PATHS['Partials']['User']['FileSelect_Carousel']); ?>

        <!-- FOOTER -->
        <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
    </div>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script src="<?php echo asset('js/toast.js') ?>"></script>
<script>
    Sortable.create(document.querySelector('#sortableContentHomeCarousel'), {
        group: 'home-carousel',
        animation: 150,
        handle: '.dragger-handle',
        direction: 'horizontal', // Ensure horizontal dragging
        filter: '.ignore-drag',
        onEnd: function(evt) {
            console.log("Element moved from position", evt.oldIndex, "to", evt.newIndex);
        }
    });
    Sortable.create(document.querySelector('#sortableContentDashboardCarousel'), {
        group: 'home-carousel',
        animation: 150,
        handle: '.dragger-handle',
        direction: 'horizontal', // Ensure horizontal dragging
        filter: '.ignore-drag',
        onEnd: function(evt) {
            console.log("Element moved from position", evt.oldIndex, "to", evt.newIndex);
        }
    });

    document.querySelectorAll('.btn-remove').forEach(function(button) {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this item?')) {
                this.closest('.sortable-item').remove();
                // AJAX to delete from server
            }
        });
    });
</script>

<?php
// Show Toast
if (isset($_SESSION["_ResultMessage"])) {
    makeToast([
        'type' => $_SESSION["_ResultMessage"]['success'] ? 'success' : 'error',
        'message' => $_SESSION["_ResultMessage"]['message'],
    ]);
    outputToasts(); // Execute toast on screen.
    unset($_SESSION["_ResultMessage"]); // Dispose
}

?>

</html>
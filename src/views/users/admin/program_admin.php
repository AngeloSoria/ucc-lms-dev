<?php
require_once '../../../../src/config/connection.php'; // Include the controller
include_once "../../../../src/config/rootpath.php";
require_once '../../../../src/controllers/ProgramController.php'; // Database connection

session_start(); // Start the session at the top of your file

// Generate a CSRF token if one doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$database = new Database();
$db = $database->getConnection(); // Establish the database connection

$programControler = new ProgramController($db);
$programList = $programControler->getAllPrograms(); // Fetch all programs

$CURRENT_PAGE = "programs";

// At the beginning of your main file
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form inputs
    $programData = [
        'program_code' => $_POST["program_code"],
        'program_name' => $_POST['program_name'],
        'program_description' => $_POST['program_description'],
        'educational_level' => $_POST['educational_level'],
        'program_image' => $_FILES['program_image'] // Handle the image upload
    ];

    // You can create a method in your ProgramController to handle the insert
    $addProgramResult = $programControler->addProgram($programData);

    // Optional: Store the result in a session variable or handle success/failure
    if ($addProgramResult) {
        // Success, maybe redirect or show a success message
        $_SESSION['message'] = "Program added successfully.";
    } else {
        // Handle error
        $_SESSION['message'] = "Failed to add program.";
    }

    // Redirect to the same page or handle as needed
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<?php include_once "../../partials/head.php" ?>

<body>
    <?php include_once '../../users/navbar.php' ?>

    <section class="d-flex justify-content-between gap-2 box-sizing-border-box m-0 p-0">
        <!-- SIDEBAR -->
        <?php include_once '../../users/sidebar.php' ?>

        <!-- content here -->
        <section class="row min-vh-100 w-100 m-0 p-1 d-flex justify-content-end align-items-start" id="contentSection">
            <div class="col box-sizing-border-box flex-grow-1">
                <!-- First row, first column -->
                <div class="bg-white rounded p-3 shadow-sm border">
                    <!-- Headers -->
                    <div class="mb-3 row align-items-start">
                        <div class="col-4 d-flex gap-3">
                            <h5 class="ctxt-primary">Programs</h5>
                        </div>
                        <div class="col-8 d-flex justify-content-end gap-2">
                            <!-- Tools -->
                            <button class="btn btn-primary btn-sm rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center" data-bs-toggle="modal" data-bs-target="#programFormModal">
                                <i class="bi bi-plus-circle"></i> Add Program
                            </button>
                            <button class="btn btn-outline-primary btn-sm rounded fs-5 px-2 c-primary d-flex gap-2 align-items-center">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                            <div class="btn-group" id="viewTypeContainer">
                                <button id="btnViewTypeCatalog" type="button" class="btn btn-sm btn-primary c-primary px-2">
                                    <i class="bi bi-card-heading fs-6"></i>
                                </button>
                                <button id="btnViewTypeTable" type="button" class="btn btn-sm btn-outline-primary c-primary px-2">
                                    <i class="bi bi-table fs-6"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Catalog View -->
                    <div id="data_view_catalog" class="d-flex justify-content-start align-items-start gap-2 flex-wrap">
                        <?php
                        if (!empty($programList)) {
                            foreach ($programList as $program) {
                                // Ensure the image is available before converting
                                $base64Image = !empty($program['program_image']) ? base64_encode($program['program_image']) : '';
                        ?>
                                <div class="c-card card cbg-primary text-white border-0 shadow-sm">
                                    <div class="card-preview position-relative w-100 bg-success d-flex overflow-hidden justify-content-center align-items-center" style="min-height: 200px; max-height: 200px;">
                                        <?php if ($base64Image): ?>
                                            <img src="data:image/jpeg;base64,<?php echo $base64Image; ?>" class="rounded card-img-top img-programs position-absolute top-50 start-50 translate-middle object-fit-fill" alt="<?php echo htmlspecialchars($program['program_name']); ?>">
                                        <?php else: ?>
                                            <div class="text-center text-muted">No image available</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <h6 class="card-title w-100 fw-bold bg-transparent" style="height: 4rem;"><?php echo htmlspecialchars($program['program_name']); ?></h6>
                                                <p class="card-text fs-6"><?php echo htmlspecialchars($program['program_description']); ?></p>
                                                <p class="card-text fs-6">Level: <?php echo htmlspecialchars($program['educational_level']); ?></p>
                                            </div>
                                            <div class="col-md-2 d-flex justify-content-end align-items-start">
                                                <div class="dropdown">
                                                    <button class="btn btn-lg c-primary p-0 text-white dropdown-toggle dropdown-no-icon" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                                        <li><a class="dropdown-item" href="#" onclick="">Configure</a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="">Delete</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo '<p class="text-danger">No programs available.</p>';
                        }
                        ?>
                    </div>

                    <!-- Table View -->
                    <div id="data_view_table" class="d-none">
                        <table class="c-table table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="checkbox_data_selectAll" id="checkbox_data_selectAll" class="form-check-input">
                                    </th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Level</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($programList)) {
                                    foreach ($programList as $program) {
                                ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="checkbox_data_<?php echo htmlspecialchars($program['program_code']); ?>" class="form-check-input">
                                            </td>
                                            <td><?php echo htmlspecialchars($program['program_code']); ?></td>
                                            <td><?php echo htmlspecialchars($program['program_name']); ?></td>
                                            <td><?php echo htmlspecialchars($program['program_description']); ?></td>
                                            <td><?php echo htmlspecialchars($program['program_level']); ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <li><a class="dropdown-item" href="#">Configure</a></li>
                                                        <li><a class="dropdown-item" href="#">Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="6" class="text-danger text-center">No programs available.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col bg-transparent d-flex flex-column justify-content-start align-items-center gap-2 px-1 box-sizing-border-box" id="widgetPanel">
                <!-- CALENDAR -->
                <?php include "../../partials/special/mycalendar.php" ?>
                <!-- TASKS -->
                <?php include "../../partials/special/mytasks.php" ?>
            </div>
        </section>
    </section>

    <?php include_once "../../partials/admin/modal_addProgram.php" ?>

    <!-- FOOTER -->
    <?php include_once "../../partials/footer.php" ?>
</body>
<script src="../../../../src/assets/js/admin-main.js"></script>

</html>

<script>
    $(document).ready(function() {
        // Check for error or success messages from the server
        <?php if (isset($_SESSION['message'])): ?>
            $('#notification').addClass('alert-success').text("<?php echo $_SESSION['message']; ?>").fadeIn().delay(3000).fadeOut();
            <?php unset($_SESSION['message']); ?>
        <?php elseif (isset($_SESSION['error'])): ?>
            $('#notification').addClass('alert-danger').text("<?php echo $_SESSION['error']; ?>").fadeIn().delay(3000).fadeOut();
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    });
</script>
<?php
require_once '../../../../src/config/connection.php'; // Include the database connection
include_once "../../../../src/config/rootpath.php";
require_once '../../../../src/controllers/SectionController.php';

session_start(); // Start the session at the top of your file

// Generate a CSRF token if one doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$database = new Database();
$db = $database->getConnection(); // Establish the database connection

$sectionController = new SectionController($db);
$sectionList = $sectionController->getAllSections(); // Fetch all sections

$CURRENT_PAGE = "sections";

// At the beginning of your main file
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form inputs
    $sectionData = [
        'section_name' => $_POST['section_name'],
        'educational_level' => $_POST['educational_level'],
        'program_id' => $_POST['program_id'],
        'year_level' => $_POST['year_level'],
        'semester' => $_POST['semester'],
        'adviser_id' => $_POST['adviser_id'],
        // Handle file upload for section_image if needed
        'section_image' => $_FILES['section_image'] ?? null // Adjust as necessary for file handling
    ];

    // Call the method in your SectionController to handle the insert
    $addSectionResult = $sectionController->addSection($sectionData);

    // Optional: Store the result in a session variable or handle success/failure
    if ($addSectionResult) {
        // Success, maybe redirect or show a success message
        $_SESSION['message'] = "Section added successfully.";
    } else {
        // Handle error
        $_SESSION['message'] = "Failed to add section.";
    }

    // Redirect to the same page or handle as needed
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}


$sql = "
    SELECT 
        s.section_name, 
        s.year_level, 
        s.semester,
        s.section_image, 
        u.first_name, 
        u.last_name, 
        p.program_name 
    FROM 
        sections s
    LEFT JOIN users u ON s.adviser_id = u.user_id
    LEFT JOIN programs p ON s.program_id = p.program_id;
";

try {
    // Prepare and execute the SQL statement
    $stmt = $db->prepare($sql);
    $stmt->execute();

    // Fetch the results into an associative array
    $sectionList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle error (you can also log the error message)
    echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
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
                            <h5 class="ctxt-primary">Section</h5>
                        </div>
                        <div class="col-8 d-flex justify-content-end gap-2">
                            <!-- Tools -->

                            <!-- Add New Button -->
                            <button class="btn btn-primary btn-sm rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center" data-bs-toggle="modal" data-bs-target="#sectionFormModal">
                                <i class="bi bi-plus-circle"></i> Add Section
                            </button>

                            <!-- Reload Button -->
                            <button class="btn btn-outline-primary btn-sm rounded fs-5 px-2 c-primary d-flex gap-2 align-items-center">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>

                            <!-- View Type -->
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
                        <?php foreach ($sectionList as $section) {
                            // Check if the section_image exists and convert the BLOB to base64
                            $base64Image = !empty($section['section_image']) ? 'data:image/jpeg;base64,' . base64_encode($section['section_image']) : '';
                        ?>
                            <div class="c-card card cbg-primary text-white border-0 shadow-sm rounded">
                                <div class="card-preview rounded rounded-bottom-0 position-relative w-100 bg-success d-flex overflow-hidden justify-content-center align-items-center">
                                    <?php if ($base64Image): ?>
                                        <img src="<?php echo $base64Image; ?>" class="card-img-top img-section position-absolute top-50 start-50 translate-middle object-fit-fill" alt="<?php echo htmlspecialchars($section['section_name']); ?>">
                                    <?php else: ?>
                                        <div class="text-center text-muted">No image available</div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body p-2">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h6 class="card-title w-100 fw-bold bg-transparent" style="height: 4rem;"><?php echo htmlspecialchars($section['section_name']); ?></h6>
                                            <p class="card-text fs-6"><?php echo htmlspecialchars($section['year_level'] . ' ' . $section['semester']); ?> Semester</p>
                                        </div>
                                        <div class="col-md-2 d-flex justify-content-end align-items-start">
                                            <div class="dropdown">
                                                <button class="btn btn-lg c-primary p-0 text-white dropdown-toggle dropdown-no-icon" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                                    <li><a class="dropdown-item" href="javascript:void(0);" role="button" data-bs-toggle="modal" data-bs-target="#detailsSectionModal">Details</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)" role="button" data-bs-toggle="modal" data-bs-target="#configSectionModal">Configure</a></li>
                                                    <li><a class="dropdown-item" href="#">Delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Table View -->
                    <div id="data_view_table" class="d-none">
                        <table class="c-table table">
                            <thead>
                                <tr>
                                    <th>Section Name</th>
                                    <th>Adviser</th>
                                    <th>Program</th>
                                    <th>Year Level</th>
                                    <th>Semester</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sectionList as $section) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($section['section_name']); ?></td>
                                        <td><?php echo htmlspecialchars($section['first_name'] . ' ' . $section['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($section['program_name']); ?></td>
                                        <td><?php echo htmlspecialchars($section['year_level']); ?></td>
                                        <td><?php echo htmlspecialchars($section['semester']); ?></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary">Configure</a>
                                            <a href="#" class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>


            </div>
            
            <div class="col bg-transparent d-flex flex-column justify-content-start align-items-center gap-2 px-1 box-sizing-border-box" id="widgetPanel">
                <!-- Second column spans both rows -->

                <!-- CALENDAR -->
                <?php include "../../partials/special/mycalendar.php" ?>

                <!-- TASKS -->
                <?php include "../../partials/special/mytasks.php" ?>
            </div>
        </section>

    </section>

    <section>

    </section>

    <?php include_once "../../partials/admin/modal_addSection.php" ?>
    <?php include_once "../../partials/admin/modal_detailsSection.php" ?>
    <?php include_once "../../partials/admin/modal_configSection.php" ?>

    <!-- FOOTER -->
    <?php include_once "../../partials/footer.php" ?>
</body>
<script src="../../../../src/assets/js/admin-main.js"></script>


</html>
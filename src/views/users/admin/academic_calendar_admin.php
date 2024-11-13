<?php
session_start(); // Start the session at the top of your file
$CURRENT_PAGE = "academic-calendar";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['AcademicTerm']);

$database = new Database();
$db = $database->getConnection(); // Establish the database connection
$academicTermController = new AcademicTermController();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $start_year = $_POST['start_year'];
    $end_year = $_POST['end_year'];
    $semester = $_POST['semester'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Combine academic year
    $academic_year = $start_year . ' - ' . $end_year;

    // Prepare the data array
    $data = [
        'academic_year' => $academic_year,
        'semester' => $semester,
        'start_date' => $start_date,
        'end_date' => $end_date,
    ];

    // Call the controller method to add the academic term
    $result = $academicTermController->addAcademicTerm($data);

    if ($result) {
        $_SESSION['message'] = "Academic term added successfully!";
    } else {
        $_SESSION['message'] = "Failed to add academic term.";
    }

    // Redirect or load the page again to show the message
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch the current active term
$currentTerm = $academicTermController->getCurrentActiveTerm();
// Fetch all academic terms
$allTerms = $academicTermController->getAllAcademicTerms();
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once(FILE_PATHS['Partials']['User']['Head']) ?>

<body>
    <?php require_once(FILE_PATHS['Partials']['User']['Navbar']) ?>

    <section class="d-flex justify-content-between gap-2 box-sizing-border-box m-0 p-0">
        <!-- SIDEBAR -->
        <?php require_once(FILE_PATHS['Partials']['User']['Sidebar']) ?>
        <!-- content here -->
        <section class="row min-vh-100 w-100 m-0 p-1 d-flex justify-content-end align-items-start" id="contentSection">
            <div class="col box-sizing-border-box flex-grow-1">
                <div class="bg-white rounded p-3 shadow-sm border">
                    <div>
                        <div class="mb-3 row align-items-start">
                            <div class="col-4 d-flex gap-3">
                                <h5 class="ctxt-primary">Academic Calendar</h5>
                            </div>
                            <div class="col-8 d-flex justify-content-end gap-2">
                                <button
                                    class="btn btn-primary btn-sm rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center"
                                    data-bs-toggle="modal" data-bs-target="#academicFormModal">
                                    <i class="bi bi-plus-circle"></i> Add New
                                </button>
                                <button
                                    class="btn btn-outline-primary btn-sm rounded fs-5 px-2 c-primary d-flex gap-2 align-items-center">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <section>
                        <h5>Current</h5>
                        <div class="d-flex gap-2">
                            <h6>Academic Term:</h6>
                            <p><?php echo $currentTerm ? htmlspecialchars($currentTerm['academic_year']) : 'N/A'; ?></p>
                        </div>
                        <div class="d-flex gap-2">
                            <h6>Academic Semester:</h6>
                            <p><?php echo $currentTerm ? htmlspecialchars($currentTerm['semester']) : 'N/A'; ?></p>
                        </div>
                        <div class="d-flex gap-2">
                            <h6>Start Date:</h6>
                            <p><?php echo $currentTerm ? htmlspecialchars($currentTerm['start_date']) : 'N/A'; ?></p>
                        </div>
                        <div class="d-flex gap-2">
                            <h6>End Date:</h6>
                            <p><?php echo $currentTerm ? htmlspecialchars($currentTerm['end_date']) : 'N/A'; ?></p>
                        </div>
                    </section>

                    <br>
                    <section>
                        <h5>List of Academic Year</h5>
                        <div class="border">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Year</th>
                                        <th>Semester</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Active</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allTerms as $term): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($term['academic_year']); ?></td>
                                            <td><?php echo htmlspecialchars($term['semester']); ?></td>
                                            <td><?php echo htmlspecialchars($term['start_date']); ?></td>
                                            <td><?php echo htmlspecialchars($term['end_date']); ?></td>
                                            <td><?php echo $term['is_active'] ? 'Yes' : 'No'; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>

            <div class="col bg-transparent d-flex flex-column justify-content-start align-items-center gap-2 px-1 box-sizing-border-box"
                id="widgetPanel">
                <?php require_once(FILE_PATHS['Partials']['User']['Calendar']) ?>
                <?php require_once(FILE_PATHS['Partials']['User']['Tasks']) ?>
            </div>
        </section>
    </section>

    <?php require_once(FILE_PATHS['Partials']['HighLevel']['Modals']['Academic']['Add']) ?>
    <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
</body>
<script src="<?php echo asset('js/admin-main.js') ?>"></script>

</html>
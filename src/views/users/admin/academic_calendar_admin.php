<?php
session_start(); // Start the session at the top of your file
$CURRENT_PAGE = 'AcademicPeriod';

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
include(FILE_PATHS['Controllers']['AcademicPeriod']);
require_once(FILE_PATHS['Partials']['Widgets']['Card']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
require_once(FILE_PATHS['Functions']['ToastLogger']);
checkUserAccess(['Admin']); // Ensure the user has admin access

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

// Create an instance of the AcademicPeriodController
$academicPeriodController = new AcademicPeriodController($db);

// Handle form submission for adding an academic year
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'addAcademicYearWithSemesters') {
    // Collect academic year data from POST request
    $academicData = [
        "academic_year_start" => $_POST['academic_year_start'],
        "academic_year_end" => $_POST['academic_year_end'],
        "first_semester" => [
            "start_date" => $_POST['first_semester_start'],
            "end_date" => $_POST['first_semester_end'],
        ],
        "second_semester" => [
            "start_date" => $_POST['second_semester_start'],
            "end_date" => $_POST['second_semester_end'],
        ],
        "semester" => $_POST['semester'],
        "is_active" => $_POST['is_active'],
    ];


    $_SESSION["_ResultMessage"] = $academicPeriodController->addAcademicYearWithSemesters($academicData);

    // Redirect to the same page to prevent resubmission
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

$currentDate = $academicPeriodController->showCurrentDate();
$academicPeriodController->checkAndUpdateActiveStatus();
// Fetch active terms
$activeTermsResponse = $academicPeriodController->getActiveTerms();
if ($activeTermsResponse['success']) {
    $activeTerms = $activeTermsResponse['data'];
    $currentTerm = $activeTerms[0]; // Assuming there's at least one active term
} else {
    $errorMessage = $activeTermsResponse['error'] ?? $activeTermsResponse['message'];
}

// Fetch all terms
$allTermsResponse = $academicPeriodController->getAllTerms();


if ($allTermsResponse != null) {
    if ($allTermsResponse['success'] == true) {
        $allTerms = $allTermsResponse['data'];
    } else {
        $allTerms = []; // In case of error, return an empty array
        $_SESSION["_ResultMessage"] = ['error', $allTermsResponse['message']];
    }
} else {
    $_SESSION["_ResultMessage"] = ['error', 'Error retrieving all terms'];
}

?>


<!DOCTYPE html>
<html lang="en">
<?php require_once(FILE_PATHS['Partials']['User']['Head']) ?>

<body>
    <div class="wrapper shadow-sm border">
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
                                    <h5 class="ctxt-primary">Academic Period Settings</h5>
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
                            <h5>Active Academic Period</h5>
                            <div class="d-flex gap-2 pt-2 ">
                                <h6>Academic Year:</h6>
                                <p>
                                    <?php echo isset($currentTerm) ? htmlspecialchars($currentTerm['academic_year_start']) . ' - ' . htmlspecialchars($currentTerm['academic_year_end']) : 'N/A'; ?>
                                </p>
                            </div>

                            <div class="d-flex gap-2">
                                <h6>Academic Semester:</h6>
                                <?php
                                if (isset($currentTerm['semester'])) {
                                    // Assuming $currentTerm['semester'] contains the year (1 or 2)
                                    if ($currentTerm['semester'] == 1) {
                                        echo '1st Semester';
                                    } elseif ($currentTerm['semester'] == 2) {
                                        echo '2nd Semester';
                                    } else {
                                        echo 'N/A';
                                    }
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </div>
                            <div class="d-flex gap-2">
                                <h6>Start Date:</h6>
                                <p><?php echo isset($currentTerm['start_date']) ? htmlspecialchars($currentTerm['start_date']) : 'N/A'; ?>
                                </p>
                            </div>
                            <div class="d-flex gap-2">
                                <h6>End Date:</h6>
                                <p><?php echo isset($currentTerm['end_date']) ? htmlspecialchars($currentTerm['end_date']) : 'N/A'; ?>
                                </p>
                            </div>
                        </section>
                        <br>
                        <section>
                            <h5>List of Academic Term</h5>
                            <div class="border">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Academic Year</th>
                                            <th>Semester</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Active</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (is_array($allTerms) && !empty($allTerms)): ?>
                                            <?php foreach ($allTerms as $term): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($term['academic_year_start'] ?? 'N/A') . ' - ' . htmlspecialchars($term['academic_year_end'] ?? 'N/A'); ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if (isset($term['semester'])) {
                                                            // Assuming $term['semester'] contains the year (1 or 2)
                                                            if ($term['semester'] == 1) {
                                                                echo '1st Semester';
                                                            } elseif ($term['semester'] == 2) {
                                                                echo '2nd Semester';
                                                            } else {
                                                                echo 'N/A';
                                                            }
                                                        } else {
                                                            echo 'N/A';
                                                        }
                                                        ?>
                                                    </td>

                                                    <td><?php echo htmlspecialchars($term['start_date'] ?? 'N/A'); ?></td>
                                                    <td><?php echo htmlspecialchars($term['end_date'] ?? 'N/A'); ?></td>
                                                    <td><?php echo isset($term['is_active']) && $term['is_active'] ? 'Yes' : 'No'; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5">No academic period available</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>

                                </table>
                            </div>
                        </section>
                    </div>
                </div>
            </section>
        </section>

        <?php require_once(FILE_PATHS['Partials']['HighLevel']['Modals']['Academic']['Add']) ?>
        <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
    </div>
</body>
<script src="<?php asset('js/admin-main.js') ?>"></script>
<script src="<?php echo asset('js/toast.js') ?>"></script>

<?php
// Show Toast
if (isset($_SESSION["_ResultMessage"]) && isset($_SESSION["_ResultMessage"]['success'])) {
    $type = $_SESSION["_ResultMessage"]['success'] ? 'success' : 'error';
    $text = isset($_SESSION["_ResultMessage"]['message']) ? $_SESSION["_ResultMessage"]['message'] : 'No message passed.';
    makeToast([
        'type' => $type,
        'message' => $text,
    ]);
    outputToasts(); // Execute toast on screen.
    unset($_SESSION["_ResultMessage"]); // Dispose
}

?>

</html>
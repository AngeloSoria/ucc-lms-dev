<?php
session_start();
$CURRENT_PAGE = "StudentSections";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['StudentSection']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
require_once(FILE_PATHS['Functions']['ToastLogger']);
checkUserAccess(['Admin']);

$database = new Database();
$db = $database->getConnection();

$studentSectionController = new StudentSectionController($db);

// Handle form submission to add students to a section
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'addStudentSection') {
        $studentSectionData = [
            'student_ids' => $_POST['student_ids'], // Array of student IDs
            'section_id' => $_POST['section_id'],
            'enrollment_type' => $_POST['enrollment_type'],
        ];
        $_SESSION["_ResultMessage"] = $studentSectionController->addStudentsToSection($studentSectionData);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } elseif (isset($_POST['search_type'])) {
        // AJAX handling for search functionality
        $searchType = $_POST['search_type'];
        $searchQuery = $_POST['query'];

        $response = $studentSectionController->fetchSearchResults($searchType, $searchQuery);
        echo json_encode($response);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Student Sections</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Add Students to Section</h1>

        <!-- Add Students to Section Form -->
        <form id="addStudentSectionForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="addStudentSection">

            <!-- Search and add students -->
            <div class="mb-3">
                <label for="student_ids" class="form-label">Select Students</label>
                <select class="form-control" id="student_ids" name="student_ids[]" multiple required></select>
            </div>

            <!-- Select one section -->
            <div class="mb-3">
                <label for="section_id" class="form-label">Select Section</label>
                <select class="form-control" id="section_id" name="section_id" required></select>
            </div>

            <!-- Enrollment type -->
            <div class="mb-3">
                <label for="enrollment_type" class="form-label">Enrollment Type</label>
                <select class="form-select" id="enrollment_type" name="enrollment_type" required>
                    <option value="" disabled selected>Select Enrollment Type</option>
                    <option value="Regular">Regular</option>
                    <option value="Irregular">Irregular</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary c-primary" form="addStudentSectionForm">Save changes</button>
        </form>

        <div class="mt-3 text-center">
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            function initializeSelect2() {
                // Initialize select2 for student search with multiple selection enabled
                $('#student_ids').select2({
                    placeholder: "Search and select students",
                    ajax: {
                        url: "",
                        type: "POST",
                        dataType: "json",
                        delay: 250,
                        data: function (params) {
                            return { search_type: "student", query: params.term };
                        },
                        processResults: function (data) {
                            return {
                                results: data.map(student => ({
                                    id: student.user_id,
                                    text: `${student.name} (${student.user_id})`
                                }))
                            };
                        }
                    }
                });

                // Initialize select2 for section search with single selection only
                $('#section_id').select2({
                    placeholder: "Search and select a section",
                    allowClear: true,
                    ajax: {
                        url: "",
                        type: "POST",
                        dataType: "json",
                        delay: 250,
                        data: function (params) {
                            return { search_type: "section", query: params.term };
                        },
                        processResults: function (data) {
                            return {
                                results: data.map(section => ({
                                    id: section.section_id,
                                    text: `${section.name} (${section.section_id})`
                                }))
                            };
                        }
                    }
                });
            }

            // Add event listener for form elements
            $(document).on('change', '#student_ids, #section_id', function (e) {
                console.log('Selection updated:', e.target.value);
            });

            // Initialize select2 inputs
            initializeSelect2();
        });
    </script>
</body>

<?php
if (isset($_SESSION["_ResultMessage"])) {
    makeToast([
        'type' => $_SESSION["_ResultMessage"]["success"] ? "success" : "error",
        'message' => $_SESSION["_ResultMessage"]["message"],
    ]);
    outputToasts();
    unset($_SESSION["_ResultMessage"]);
}
?>

</html>
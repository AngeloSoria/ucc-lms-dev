<?php
session_start();
$CURRENT_PAGE = "StudentSections";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['StudentEnrollment']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
require_once(FILE_PATHS['Functions']['ToastLogger']);
checkUserAccess(['Admin']);


$database = new Database();
$db = $database->getConnection();

$enrollmentController = new StudentEnrollmentController($db);

// Handle form submission for adding students to a subject_section
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'addStudentEnrollment') {
        $studentEnrollmentData = [
            'student_ids' => 1008, // Array of student IDs
            'subject_section_id' => 14,
        ];

        // Call the controller to handle business logic
        $result = $enrollmentController->addStudentsToSubjectSection($studentEnrollmentData);
        echo json_encode($result); // Send a JSON response
        exit();
    } elseif (isset($_POST['search_type'])) {
        // Handle AJAX search for students or sections
        $searchType = $_POST['search_type'];
        $query = $_POST['query'];

        $response = $enrollmentController->fetchSearchResults($searchType, $query);
        echo json_encode($response); // Send a JSON response
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Student Enrollment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Add Students to Subject Section</h1>

        <!-- Add Students to Subject Section Form -->
        <form id="addStudentEnrollmentForm">

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

</body>

</html>
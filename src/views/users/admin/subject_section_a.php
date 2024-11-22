<?php
session_start();
$CURRENT_PAGE = "SubjectSections";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['SubjectSection']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
require_once(FILE_PATHS['Functions']['ToastLogger']);
checkUserAccess(['Admin']);

$database = new Database();
$db = $database->getConnection();

$subjectSectionController = new SubjectSectionController($db);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'addSubjectSection') {
        $subjectSectionData = [
            'section_id' => $_POST['section_id'],
            'subject_ids' => $_POST['subject_ids'], // Array of student IDs
            'teacher_id' => $_POST['teacher_id'], // Added teacher ID
        ];

        // Handle file upload for subject_section_image
        if (!empty($_FILES['subject_section_image']['tmp_name'])) {
            $studentSectionData['image'] = file_get_contents($_FILES['subject_section_image']['tmp_name']);
        }

        $_SESSION["_ResultMessage"] = $subjectSectionController->addSubjectSection($subjectSectionData);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } elseif (isset($_POST['search_type'])) {
        $searchType = $_POST['search_type'];
        $searchQuery = $_POST['query'];
        $additionalFilters = $_POST['additional_filters'] ?? [];

        $response = $subjectSectionController->fetchSearchResults($searchType, $searchQuery, $additionalFilters);
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
        <h1 class="text-center mb-4">Subject to Section</h1>

        <!-- Add Students to Section Form -->
        <form id="addSubjectSectionForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="addSubjectSection">

            <div class="form-group">
                <label for="educational_level_filter">Educational Type</label>
                <select id="educational_level_filter" class="form-control">
                    <option value="">All</option>
                    <option value="SHS">SHS</option>
                    <option value="College">College</option>
                </select>
            </div>

            <!-- Select a section -->
            <div class="mb-3">
                <label for="section_id" class="form-label">Select Section</label>
                <select class="form-control" id="section_id" name="section_id" required></select>
            </div>

            <!-- Select students -->
            <div class="mb-3">
                <label for="subject_ids" class="form-label">Select Students</label>
                <select class="form-control" id="subject_ids" name="subject_ids[]" multiple required></select>
            </div>

            <!-- Select or assign teacher -->
            <div class="mb-3">
                <label for="teacher_id" class="form-label">Assign Teacher</label>
                <select class="form-control" id="teacher_id" name="teacher_id" required></select>
            </div>

            <!-- Upload subject section image -->
            <div class="mb-3">
                <label for="subject_section_image" class="form-label">Upload Section Image</label>
                <input type="file" class="form-control" id="subject_section_image" name="subject_section_image"
                    accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Save changes</button>
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

                $('#subject_ids').select2({
                    placeholder: "Search and select subjects",
                    ajax: {
                        url: "",  // Empty URL to use the current URL
                        type: "POST",
                        dataType: "json",
                        delay: 250,
                        data: function (params) {
                            return {
                                search_type: "subject",
                                query: params.term,  // Search query from user input
                                section_id: $('#section_id').val(),  // Send the selected section_id
                                educational_level: $('#section_id').data('educational_level') // Send educational level based on section
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: data.map(function (subject) {
                                    return {
                                        id: subject.subject_id,
                                        text: `${subject.name} (${subject.semester})`
                                    };
                                })
                            };
                        }
                    }
                });


                // FOR ADVISER THIS FUNCTION

                $(document).ready(function () {
                    // Initialize the dropdown for teacher search with educational level filtering
                    $('#teacher_id').select2({
                        placeholder: "Search and select a teacher",
                        ajax: {
                            url: "", // Replace with your endpoint
                            type: "POST",
                            dataType: "json",
                            delay: 250,
                            data: function (params) {
                                return {
                                    search_type: "teacher",
                                    query: params.term,
                                    additional_filters: {
                                        educational_level: $('#educational_level_filter').val() // Get the selected educational type
                                    }
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: data.map(teacher => ({
                                        id: teacher.user_id,
                                        text: `${teacher.name} (${teacher.educational_level})`
                                    }))
                                };
                            }
                        }
                    });

                    // Optional: Update the teacher list dynamically when the educational type changes
                    $('#educational_level_filter').change(function () {
                        $('#teacher_id').val(null).trigger('change'); // Clear and reload teacher select2 options
                    });
                });

            }

            initializeSelect2();
        });
    </script>

</body>
<script src="<?php echo asset('js/toast.js') ?>"></script>
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
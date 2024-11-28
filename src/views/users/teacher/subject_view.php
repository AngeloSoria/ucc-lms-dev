<?php
session_start();
$CURRENT_PAGE = "dashboard";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);

require_once(FILE_PATHS['Controllers']['User']);
require_once(FILE_PATHS['Controllers']['SubjectSection']);
require_once(FILE_PATHS['Controllers']['Subject']);
require_once(FILE_PATHS['Controllers']['Section']);

require_once(FILE_PATHS['Functions']['SessionChecker']);
require_once(FILE_PATHS['Functions']['ToastLogger']);
require_once(FILE_PATHS['Functions']['UpdateURLParams']);

checkUserAccess(['Teacher']);

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

$subjectSectionController = new SubjectSectionController($db);
$subjectController = new SubjectController();
$sectionController = new SectionController();

// Create an instance of the UserController
$userController = new UserController();

if (!isset($_GET['subject_section_id'])) {
    header("Location: " . BASE_PATH_LINK);
    exit();
}

// PRELOAD INFO
$SUBJECT_SECTION_INFO = $subjectSectionController->getSubjectSectionDetails($_GET['subject_section_id']);
if (!$SUBJECT_SECTION_INFO['success']) {
    header("Location: " . ERROR_PATH);
    exit();
}

$SUBJECT_INFO = $subjectController->getSubjectFromSubjectId($SUBJECT_SECTION_INFO['data']['subject_id']);
$SECTION_INFO = $sectionController->getSectionById($SUBJECT_SECTION_INFO['data']['section_id']);
// $TEACHER_INFO = $
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once(FILE_PATHS['Partials']['User']['Head']) ?>

<body data-theme="light">
    <div class="wrapper shadow-sm border">
        <?php require_once(FILE_PATHS['Partials']['User']['Navbar']) ?>

        <section class="d-flex justify-content-between gap-2 box-sizing-border-box m-0 p-0">
            <!-- SIDEBAR -->
            <?php require_once(FILE_PATHS['Partials']['User']['Sidebar2']) ?>

            <!-- content here -->
            <section id="contentSection" class="row d-flex justify-content-start align-items-start">
                <div class="col-lg-9 p-0 box-sizing-border-box">
                    <!-- First row, first column -->
                    <div class="d-flex flex-column gap-2 flex-grow-1">
                        <div class="bg-white shadow-sm rounded px-2 pt-3">
                            <div id="top-controls" class="row m-0">
                                <div class="col-lg-8">
                                    <h5 class="text-success"><?php echo $SUBJECT_INFO['data']['subject_name'] . ' (' . $SECTION_INFO['data']['section_name'] . ')' ?></h5>
                                </div>
                                <div class="col-lg-4 d-flex justify-content-end align-items-center">
                                    <button class="btn btn-success shadow-sm d-flex gap-2">
                                        <i class="bi bi-plus-square"></i>
                                        Add Module
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <section id="modules_container" class="d-flex flex-column gap-3 mb-3">

                                <div class="accordion rounded shadow-sm border overflow-hidden" id="module_1">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingModule1">
                                            <button
                                                class="accordion-button bg-success bg-opacity-75 text-white fs-5 fw-medium"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseModule1"
                                                aria-expanded="true"
                                                aria-controls="collapseModule1">
                                                1. Syllabus & Orientations
                                            </button>
                                        </h2>
                                        <div
                                            id="collapseModule1"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="headingModule1"
                                            data-bs-parent="#module_1">
                                            <div class="accordion-body">
                                                <div class="d-flex justify-content-end align-items-center gap-2 mb-3">
                                                    <button class="btn btn-sm btn-primary shadow-sm text-white" title="Edit this Module">
                                                        <i class="bi bi-pencil-square"></i>
                                                        Edit Module
                                                    </button>
                                                </div>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex gap-2 align-items-center">
                                                        <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                                        <a href="#" class="link-body-emphasis">Syllabus Introduction</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-earmark-ppt-fill text-critical"></i>
                                                        <a href="#" class="link-body-emphasis">Intro to Subject</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-earmark-text-fill text-primary"></i>
                                                        <a href="#" class="link-body-emphasis">Notes</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-word-fill text-primary"></i>
                                                        <a href="#" class="link-body-emphasis">Template 1</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-earmark-ppt-fill text-critical"></i>
                                                        <a href="#" class="link-body-emphasis">DSA 3 Prelims Presentation</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-stickies-fill text-warning"></i>
                                                        <a href="#" class="link-body-emphasis">Online Activity 1</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-stickies-fill text-warning"></i>
                                                        <a href="#" class="link-body-emphasis">Quiz 1</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion rounded shadow-sm border overflow-hidden" id="module_2">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingModule1">
                                            <button
                                                class="accordion-button collapsed bg-success bg-opacity-75 text-white fs-5 fw-medium"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseModule2"
                                                aria-expanded="false"
                                                aria-controls="collapseModule2">
                                                2. Mastering the Basics (Prelims)
                                            </button>
                                        </h2>
                                        <div
                                            id="collapseModule2"
                                            class="accordion-collapse collapse"
                                            aria-labelledby="headingModule1"
                                            data-bs-parent="#module_2">
                                            <div class="accordion-body">
                                                <div class="d-flex justify-content-end align-items-center gap-2 mb-3">
                                                    <button class="btn btn-sm btn-primary shadow-sm text-white" title="Edit this Module">
                                                        <i class="bi bi-pencil-square"></i>
                                                        Edit Module
                                                    </button>
                                                </div>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex gap-2 align-items-center">
                                                        <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                                        <a href="#" class="link-body-emphasis">Syllabus Introduction</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-earmark-ppt-fill text-critical"></i>
                                                        <a href="#" class="link-body-emphasis">Intro to Subject</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-earmark-text-fill text-primary"></i>
                                                        <a href="#" class="link-body-emphasis">Notes</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-word-fill text-primary"></i>
                                                        <a href="#" class="link-body-emphasis">Template 1</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-earmark-ppt-fill text-critical"></i>
                                                        <a href="#" class="link-body-emphasis">DSA 3 Prelims Presentation</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-stickies-fill text-warning"></i>
                                                        <a href="#" class="link-body-emphasis">Online Activity 1</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-stickies-fill text-warning"></i>
                                                        <a href="#" class="link-body-emphasis">Quiz 1</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion rounded shadow-sm border overflow-hidden" id="module_3">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingModule1">
                                            <button
                                                class="accordion-button collapsed bg-success bg-opacity-75 text-white fs-5 fw-medium"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseModule3"
                                                aria-expanded="false"
                                                aria-controls="collapseModule3">
                                                3. Breathing Exercises (Midterms)
                                            </button>
                                        </h2>
                                        <div
                                            id="collapseModule3"
                                            class="accordion-collapse collapse"
                                            aria-labelledby="headingModule1"
                                            data-bs-parent="#module_3">
                                            <div class="accordion-body">
                                                <div class="d-flex justify-content-end align-items-center gap-2 mb-3">
                                                    <button class="btn btn-sm btn-primary shadow-sm text-white" title="Edit this Module">
                                                        <i class="bi bi-pencil-square"></i>
                                                        Edit Module
                                                    </button>
                                                </div>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex gap-2 align-items-center">
                                                        <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                                        <a href="#" class="link-body-emphasis">Syllabus Introduction</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-earmark-ppt-fill text-critical"></i>
                                                        <a href="#" class="link-body-emphasis">Intro to Subject</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-earmark-text-fill text-primary"></i>
                                                        <a href="#" class="link-body-emphasis">Notes</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-word-fill text-primary"></i>
                                                        <a href="#" class="link-body-emphasis">Template 1</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-earmark-ppt-fill text-critical"></i>
                                                        <a href="#" class="link-body-emphasis">DSA 3 Prelims Presentation</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-stickies-fill text-warning"></i>
                                                        <a href="#" class="link-body-emphasis">Online Activity 1</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-stickies-fill text-warning"></i>
                                                        <a href="#" class="link-body-emphasis">Quiz 1</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion rounded shadow-sm border overflow-hidden" id="module_4">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingModule1">
                                            <button
                                                class="accordion-button collapsed bg-success bg-opacity-75 text-white fs-5 fw-medium"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseModule4"
                                                aria-expanded="false"
                                                aria-controls="collapseModule4">
                                                3. Advanced Marathon (Prefinals)
                                            </button>
                                        </h2>
                                        <div
                                            id="collapseModule4"
                                            class="accordion-collapse collapse"
                                            aria-labelledby="headingModule1"
                                            data-bs-parent="#module_4">
                                            <div class="accordion-body">
                                                <div class="d-flex justify-content-end align-items-center gap-2 mb-3">
                                                    <button class="btn btn-sm btn-primary shadow-sm text-white" title="Edit this Module">
                                                        <i class="bi bi-pencil-square"></i>
                                                        Edit Module
                                                    </button>
                                                </div>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex gap-2 align-items-center">
                                                        <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                                        <a href="#" class="link-body-emphasis">Syllabus Introduction</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-earmark-ppt-fill text-critical"></i>
                                                        <a href="#" class="link-body-emphasis">Intro to Subject</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-earmark-text-fill text-primary"></i>
                                                        <a href="#" class="link-body-emphasis">Notes</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-word-fill text-primary"></i>
                                                        <a href="#" class="link-body-emphasis">Template 1</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-earmark-ppt-fill text-critical"></i>
                                                        <a href="#" class="link-body-emphasis">DSA 3 Prelims Presentation</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-stickies-fill text-warning"></i>
                                                        <a href="#" class="link-body-emphasis">Online Activity 1</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-stickies-fill text-warning"></i>
                                                        <a href="#" class="link-body-emphasis">Quiz 1</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion rounded shadow-sm border overflow-hidden" id="module_5">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingModule1">
                                            <button
                                                class="accordion-button collapsed bg-success bg-opacity-75 text-white fs-5 fw-medium"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapseModule5"
                                                aria-expanded="false"
                                                aria-controls="collapseModule5">
                                                3. Group Project (Finals)
                                            </button>
                                        </h2>
                                        <div
                                            id="collapseModule5"
                                            class="accordion-collapse collapse"
                                            aria-labelledby="headingModule1"
                                            data-bs-parent="#module_5">
                                            <div class="accordion-body">
                                                <div class="d-flex justify-content-end align-items-center gap-2 mb-3">
                                                    <button class="btn btn-sm btn-primary shadow-sm text-white" title="Edit this Module">
                                                        <i class="bi bi-pencil-square"></i>
                                                        Edit Module
                                                    </button>
                                                </div>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex gap-2 align-items-center">
                                                        <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                                        <a href="#" class="link-body-emphasis">Syllabus Introduction</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-earmark-ppt-fill text-critical"></i>
                                                        <a href="#" class="link-body-emphasis">Intro to Subject</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-earmark-text-fill text-primary"></i>
                                                        <a href="#" class="link-body-emphasis">Notes</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-word-fill text-primary"></i>
                                                        <a href="#" class="link-body-emphasis">Template 1</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-file-earmark-ppt-fill text-critical"></i>
                                                        <a href="#" class="link-body-emphasis">DSA 3 Prelims Presentation</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-stickies-fill text-warning"></i>
                                                        <a href="#" class="link-body-emphasis">Online Activity 1</a>
                                                    </li>
                                                    <li class="list-group-item d-flex gap-2">
                                                        <i class="bi bi-stickies-fill text-warning"></i>
                                                        <a href="#" class="link-body-emphasis">Quiz 1</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </section>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 mt-md-2 mt-lg-0 px-sm-0 px-md-1 d-flex flex-column gap-1">
                    <?php require_once(FILE_PATHS['Partials']['User']['Tasks']) ?>
                    <?php require_once(FILE_PATHS['Partials']['User']['Announcements']) ?>
                </div>
            </section>
        </section>

        <!-- FOOTER -->
        <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
    </div>
</body>
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
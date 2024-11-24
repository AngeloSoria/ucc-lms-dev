<?php
session_start();
$CURRENT_PAGE = "dashboard";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['User']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
require_once(FILE_PATHS['Functions']['ToastLogger']);
checkUserAccess(['Teacher']);

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

// Create an instance of the UserController
$userController = new UserController();

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
                            <div id="page-context my-3">
                                <h5 class="text-success">Data Structures & Algorithms III</h5>
                            </div>
                            <hr>
                            <section id="modules_container" class="d-flex flex-column gap-3 mb-3">

                                <div class="rounded shadow-sm border overflow-hidden" id="module_1">
                                    <div class="banner py-3 px-2  bg-success bg-opacity-75">
                                        <p class="text-white fs-6 fw-medium">1. Syllabus</p>
                                    </div>
                                    <div id="subcontent">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                                <a href="#" class="link-body-emphasis">Syllabus Introduction.pdf</a>
                                            </li>
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-file-earmark-ppt-fill text-critical"></i>
                                                <a href="#" class="link-body-emphasis">Intro to Subject.pptx</a>
                                            </li>
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-file-earmark-text-fill text-primary"></i>
                                                <a href="#" class="link-body-emphasis">Notes.txt</a>
                                            </li>
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-file-word-fill text-primary"></i>
                                                <a href="#" class="link-body-emphasis">Template 1.docx</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="rounded shadow-sm border overflow-hidden" id="module_1">
                                    <div class="banner py-3 px-2  bg-success bg-opacity-75">
                                        <p class="text-white fs-6 fw-medium">2. Introduction to Subject (PRELIMS)</p>
                                    </div>
                                    <div id="subcontent">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                                <a href="#" class="link-body-emphasis">01 Handout 1 - DSA 3 Prelims.pdf</a>
                                            </li>
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-file-earmark-ppt-fill text-critical"></i>
                                                <a href="#" class="link-body-emphasis">DSA 3 Prelims Presentation.pptx</a>
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

                                <div class="rounded shadow-sm border overflow-hidden" id="module_1">
                                    <div class="banner py-3 px-2  bg-success bg-opacity-75">
                                        <p class="text-white fs-6 fw-medium">3. Mastering the Concepts (MIDTERMS)</p>
                                    </div>
                                    <div id="subcontent">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                                <a href="#" class="link-body-emphasis">02 Handout 1 - DSA 3 Midterms.pdf</a>
                                            </li>
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-file-earmark-ppt-fill text-critical"></i>
                                                <a href="#" class="link-body-emphasis">DSA 3 Midterms Presentation.pptx</a>
                                            </li>
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-stickies-fill text-warning"></i>
                                                <a href="#" class="link-body-emphasis">Online Activity 2</a>
                                            </li>
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-stickies-fill text-warning"></i>
                                                <a href="#" class="link-body-emphasis">Online Activity 3</a>
                                            </li>
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-stickies-fill text-warning"></i>
                                                <a href="#" class="link-body-emphasis">Quiz 2</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="rounded shadow-sm border overflow-hidden" id="module_1">
                                    <div class="banner py-3 px-2  bg-success bg-opacity-75">
                                        <p class="text-white fs-6 fw-medium">3. Algorithms (PREFINALS)</p>
                                    </div>
                                    <div id="subcontent">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                                <a href="#" class="link-body-emphasis">03 Handout 1 - DSA 3 Midterms.pdf</a>
                                            </li>
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                                <a href="#" class="link-body-emphasis">03 Handout 2 - DSA 3 Midterms.pdf</a>
                                            </li>
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-file-earmark-ppt-fill text-critical"></i>
                                                <a href="#" class="link-body-emphasis">DSA 3 Prefinals Presentation.pptx</a>
                                            </li>
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-stickies-fill text-warning"></i>
                                                <a href="#" class="link-body-emphasis">Online Activity 2</a>
                                            </li>
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-stickies-fill text-warning"></i>
                                                <a href="#" class="link-body-emphasis">Online Activity 3</a>
                                            </li>
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-stickies-fill text-warning"></i>
                                                <a href="#" class="link-body-emphasis">Quiz 2</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="rounded shadow-sm border overflow-hidden" id="module_1">
                                    <div class="banner py-3 px-2  bg-success bg-opacity-75">
                                        <p class="text-white fs-6 fw-medium">3. Concept Projects (FINALS)</p>
                                    </div>
                                    <div id="subcontent">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                                <a href="#" class="link-body-emphasis">02 Handout 1 - DSA 3 Midterms.pdf</a>
                                            </li>
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-file-earmark-ppt-fill text-critical"></i>
                                                <a href="#" class="link-body-emphasis">DSA 3 Midterms Presentation.pptx</a>
                                            </li>
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-stickies-fill text-warning"></i>
                                                <a href="#" class="link-body-emphasis">Online Activity 2</a>
                                            </li>
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-stickies-fill text-warning"></i>
                                                <a href="#" class="link-body-emphasis">Online Activity 3</a>
                                            </li>
                                            <li class="list-group-item d-flex gap-2">
                                                <i class="bi bi-stickies-fill text-warning"></i>
                                                <a href="#" class="link-body-emphasis">Quiz 2</a>
                                            </li>
                                        </ul>
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
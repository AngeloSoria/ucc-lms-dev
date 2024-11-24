<?php
session_start(); // Start the session at the top of your file
$CURRENT_PAGE = "subjects";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['Subject']);

require_once(FILE_PATHS['Partials']['Widgets']['Card']);
require_once(FILE_PATHS['Partials']['Widgets']['DataTable']);

require_once(FILE_PATHS['Functions']['UpdateURLParams']);
require_once(FILE_PATHS['Functions']['SessionChecker']);
require_once(FILE_PATHS['Functions']['ToastLogger']);

checkUserAccess(['Admin']);

$widget_card = new Card();

$subjectController = new SubjectController();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'addSubject') {
    // Collect user data from form inputs
    $subjectData = [
        'subject_code' => $_POST['subject_code'],
        'subject_name' => $_POST['subject_name'],
        'semester' => $_POST['semester'],
        'educational_level' => $_POST['educational_level']
    ];

    $_SESSION["_ResultMessage"] = $subjectController->addSubject($subjectData);

    // Redirect to the same page to prevent resubmission
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

// GET ALL SUBJECTS
$RETRIEVED_SUBJECTS = $subjectController->getAllSubjects();
if ($RETRIEVED_SUBJECTS['success'] == false) {
    $_SESSION["_ResultMessage"] = $RETRIEVED_SUBJECTS;
} else {
    if (isset($_GET['viewSubject'])) {
        $SELECTED_SUBJECT = null;
        foreach ($RETRIEVED_SUBJECTS['data'] as $subject) {
            if ($subject['subject_id'] == $_GET['viewSubject']) {
                $SELECTED_SUBJECT = $subject;
            }
        }
        if (!isset($SELECTED_SUBJECT)) {
            $subject_id_passed = $_GET['viewSubject'];
            $_SESSION["_ResultMessage"] = ['success' => false, 'message' => "Invalid subject id of ($subject_id_passed) passed."];
            header('Location: ' . clearUrlParams());
            exit();
        }
    }
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
            <section id="contentSection">
                <div class="col box-sizing-border-box flex-grow-1">
                    <?php if (!isset($_GET['viewSubject'])): ?>
                        <div class="bg-white rounded p-3 shadow-sm border">
                            <!-- Headers -->
                            <div class="mb-3 row align-items-start">
                                <div class="col-4 d-flex gap-3">
                                    <h5 class="ctxt-primary">Subjects <?php //print_r($RETRIEVED_SUBJECTS['data']); 
                                                                        ?></h5>
                                </div>
                                <div class="col-8 d-flex justify-content-end gap-2">
                                    <!-- Tools -->

                                    <!-- Add New Button -->
                                    <button
                                        class="btn btn-primary btn-sm rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center"
                                        data-bs-toggle="modal" data-bs-target="#createSubjectModal">
                                        <i class="bi bi-plus-circle"></i> Add Subject
                                    </button>

                                    <!-- Reload Button -->
                                    <button
                                        class="btn btn-outline-primary btn-sm rounded fs-5 px-2 c-primary d-flex gap-2 align-items-center">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>

                                    <!-- Preview Type -->
                                    <div class="btn-group" id="previewTypeContainer">
                                        <a id="btnPreviewTypeCatalog" type="button" preview-container-target="view_catalog"
                                            class="btn btn-sm btn-primary c-primary px-2 d-flex justify-content-center align-items-center">
                                            <i class="bi bi-card-heading fs-6"></i>
                                        </a>
                                        <a id="btnPreviewTypeTable" type="button" preview-container-target="view_table"
                                            class="btn btn-sm btn-outline-primary c-primary px-2 d-flex justify-content-center align-items-center">
                                            <i class="bi bi-table fs-6"></i>
                                        </a>
                                    </div>

                                </div>
                            </div>


                            <!-- Catalog View -->
                            <div class="row" preview-container-name="view_catalog" preview-container-default id="data_view_catalog">
                                <?php
                                // Load All Subjects
                                if (isset($RETRIEVED_SUBJECTS['data'])) {
                                    foreach ($RETRIEVED_SUBJECTS['data'] as $subject) {
                                        echo $widget_card->Create(
                                            3,
                                            $subject['subject_id'],
                                            null,
                                            [
                                                "title" => $subject['subject_name'],
                                                "others" => [
                                                    [
                                                        'hint' => 'Total Students enroled in a subject',
                                                        'icon' => '<i class="bi bi-person-fill"></i>',
                                                        'data' => number_format(22213123) . " Enrolled students",
                                                    ],
                                                ],
                                            ],
                                            false,
                                            true,
                                            updateUrlParams(['viewSubject' => $subject['subject_id']])
                                        );
                                    }
                                }
                                ?>
                            </div>

                            <!-- Table View -->
                            <div preview-container-name="view_table" id="data_view_table" class="d-none">
                                <table class="c-table table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" name="checkbox_data_selectAll"
                                                    id="checkbox_data_selectAll" class="form-check-input">
                                            </th>
                                            <th>Role</th>
                                            <th>Users</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="<data_context>"
                                                    id="checkbox_data_select-<data_context>" class="form-check-input">
                                            </td>
                                            <td>qwe</td>
                                            <td>qwe</td>
                                            <td>qwe</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- Pagination -->
                                <div class="d-flex gap-2 align-items-center justify-content-start">
                                    <div class="d-flex align-items-center gap-1">
                                        <span>Show:</span>
                                        <select id="data_view_table_show_per_page" class="form-select">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                        </select>
                                    </div>
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <li class="page-item">
                                                <a class="page-link" href="#" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="bg-white rounded p-3 shadow-sm border">
                            <div class="mb-3 row align-items-start bg-transparent box-sizing-border-box">
                                <div class="col-md-8 d-flex gap-2 justify-content-start align-items-center box-sizing-border-box">
                                    <!-- breadcrumbs -->
                                    <h5 class="ctxt-primary p-0 m-0">
                                        <a class="ctxt-primary" href="<?= clearUrlParams(); ?>">Subjects</a>
                                        <?php if (isset($_GET['viewSubject'])) { ?>
                                            <span><i class="bi bi-caret-right-fill"></i></span>
                                            <a class="ctxt-primary" href="<?= updateUrlParams(['viewSubject' => $_GET['viewSubject']]) ?>"><?= ucfirst($SELECTED_SUBJECT['subject_name']) ?></a>
                                        <?php } ?>
                                    </h5>
                                    <!-- end of breadcrumbs -->
                                </div>
                                <!-- <div class="col-8 d-flex justify-content-end gap-2"></div> -->
                            </div>
                            <!-- Content View -->
                            <?php if (!empty($SELECTED_SUBJECT)): ?>
                                <hr>
                                <!-- generated -->
                                <div class="my-4">
                                    <h4 class="fw-bolder text-success">Edit Subject</h4>
                                    <div class="card shadow-sm position-relative">
                                        <div class="card-header position-relative d-flex justify-content-start align-items-center gap-3 bg-success bg-opacity-75">
                                            <div class="position-absolute top-0 end-0 mt-3 me-4">
                                                <button class="btn cbtn-secondary px-4">
                                                    Edit
                                                </button>
                                            </div>
                                            <div class="text-white p-0 pb-2">
                                                <h3 class="mt-3 p-0 m-0"><?= htmlspecialchars($SELECTED_SUBJECT['subject_name']) ?></h3>
                                                <p class="text-white p-0 m-0"><?= htmlspecialchars($SELECTED_SUBJECT['subject_code']) ?></p>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <section class="mb-4">
                                                <div class="row mb-3">
                                                    <h5>Subject Information</h5>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-sm-6 col-md-5 col-lg-3 mb-2">
                                                        <h6 class="pt-sm-3 pt-md-0">Subject Code</h6>
                                                        <input updateEnabled class="form-control" type="text" disabled value="<?= htmlspecialchars($SELECTED_SUBJECT['subject_code']) ?>">
                                                    </div>
                                                    <div class="col-sm-12 col-md-7 col-lg-7">
                                                        <h6>Subject Name</h6>
                                                        <input updateEnabled class="form-control" type="text" disabled value="<?= htmlspecialchars($SELECTED_SUBJECT['subject_name']) ?>">
                                                    </div>
                                                    <div class="col-sm-6 col-md-6 col-lg-2">
                                                        <h6 class="pt-sm-3 pt-md-0">Semester</h6>
                                                        <select name="" id="" class="form-select" disabled>
                                                            <?php if (isset($SELECTED_SUBJECT['semester'])): ?>
                                                                <option value="<?= htmlspecialchars($SELECTED_SUBJECT['semester']) ?>"><?= htmlspecialchars($SELECTED_SUBJECT['semester']) ?></option>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <h6 class="">Educational Level</h6>
                                                        <select name="" id="" class="form-select" disabled>
                                                            <?php if (isset($SELECTED_SUBJECT['educational_level'])): ?>
                                                                <option value="<?= htmlspecialchars($SELECTED_SUBJECT['educational_level']) ?>"><?= htmlspecialchars($SELECTED_SUBJECT['educational_level']) ?></option>
                                                            <?php endif; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </section>

                                            <hr>

                                            <section class="mb-4">
                                                <div class="row mb-3">
                                                    <h5>Connected Sections</h5>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="container table-responsive">
                                                        <table class="table table-striped w-100">
                                                            <thead class="table-success">
                                                                <tr>
                                                                    <th>test</th>
                                                                    <th>test</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1</td>
                                                                    <td>1</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <h3 class="text-danger">No Information Shown.</h3>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </section>
        </section>

        <!-- ADD SUBJECT POPUP -->
        <?php require_once(FILE_PATHS['Partials']['HighLevel']['Modals']['Subject']['Add']) ?>

        <!-- FOOTER -->
        <?php require_once(FILE_PATHS['Partials']['User']['Footer']) ?>
    </div>
</body>

<script src="<?php echo asset('js/preview-handler.js') ?>"></script>
<script src="<?php echo asset('js/toast.js') ?>"></script>

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
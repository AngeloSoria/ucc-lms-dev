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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case "addSubject":
                // Collect user data from form inputs
                $subjectData = [
                    'subject_code' => $_POST['subject_code'],
                    'subject_name' => $_POST['subject_name'],
                    'semester' => $_POST['semester'],
                    'educational_level' => $_POST['educational_level']
                ];

                // Validate input before processing
                if (empty($subjectData['subject_code']) || empty($subjectData['subject_name'])) {
                    $_SESSION["_ResultMessage"] = ['success' => false, 'message' => "Subject code and name are required."];
                    break;
                }

                $_SESSION["_ResultMessage"] = $subjectController->addSubject($subjectData);
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();

            case "updateSectionInfo":
                $subjectData = [ // Use square brackets for arrays
                    'subject_id' => $_GET['viewSubject'],
                    'subject_code' => $_POST['subject_code'],
                    'subject_name' => $_POST['subject_name'],
                ];

                // Validate input before processing
                if (empty($subjectData['subject_code']) || empty($subjectData['subject_name'])) {
                    $_SESSION["_ResultMessage"] = ['success' => false, 'message' => "Subject code and name are required."];
                    break;
                }

                $_SESSION["_ResultMessage"] = $subjectController->updateSubject($subjectData['subject_id'], $subjectData);
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            case "deleteSubject":
                $subjectData = [ // Use square brackets for arrays
                    'subject_id' => $_GET['viewSubject'],
                    'subject_code' => $_POST['subject_code'],
                    'subject_name' => $_POST['subject_name'],
                ];

                $_SESSION["_ResultMessage"] = $subjectController->deleteSubject($subjectData);
                header("Content-Type: application/json");
                echo json_encode(['redirect' => $_SERVER['REQUEST_URI']]); // Send the URL to redirect to
                exit();
        }
    }
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
                                        class="btn btn-primary btn-lg rounded fs-6 px-3 c-primary d-flex gap-3 align-items-center"
                                        data-bs-toggle="modal" data-bs-target="#createSubjectModal">
                                        <i class="bi bi-plus-circle"></i> Add Subject
                                    </button>

                                    <!-- Reload Button -->
                                    <!-- <button
                                        class="btn btn-outline-primary btn-sm rounded fs-5 px-2 c-primary d-flex gap-2 align-items-center">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button> -->

                                    <!-- Preview Type -->
                                    <!-- <div class="btn-group" id="previewTypeContainer">
                                        <a id="btnPreviewTypeCatalog" type="button" preview-container-target="view_catalog"
                                            class="btn btn-sm btn-primary c-primary px-2 d-flex justify-content-center align-items-center">
                                            <i class="bi bi-card-heading fs-6"></i>
                                        </a>
                                        <a id="btnPreviewTypeTable" type="button" preview-container-target="view_table"
                                            class="btn btn-sm btn-outline-primary c-primary px-2 d-flex justify-content-center align-items-center">
                                            <i class="bi bi-table fs-6"></i>
                                        </a>
                                    </div> -->

                                </div>
                            </div>


                            <!-- Catalog View -->
                            <div class="row" preview-container-name="view_catalog" preview-container-default
                                id="data_view_catalog">
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
                                                        'hint' => 'Educational Level',
                                                        'icon' => '<i class="bi bi-person-workspace"></i>',
                                                        'data' => htmlspecialchars($subject['educational_level']),
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
                                <div
                                    class="col-md-8 d-flex gap-2 justify-content-start align-items-center box-sizing-border-box">
                                    <!-- breadcrumbs -->
                                    <h5 class="ctxt-primary p-0 m-0">
                                        <a class="ctxt-primary" href="<?= clearUrlParams(); ?>">Subjects</a>
                                        <?php if (isset($_GET['viewSubject'])) { ?>
                                            <span><i class="bi bi-caret-right-fill"></i></span>
                                            <a class="ctxt-primary"
                                                href="<?= updateUrlParams(['viewSubject' => $_GET['viewSubject']]) ?>"><?= ucfirst($SELECTED_SUBJECT['subject_name']) ?></a>
                                        <?php } ?>
                                    </h5>
                                    <!-- end of breadcrumbs -->
                                </div>
                                <!-- <div class="col-8 d-flex justify-content-end gap-2"></div> -->
                            </div>
                            <!-- Content View -->
                            <?php if (!empty($SELECTED_SUBJECT)): ?>
                                <hr>
                                <?php require_once(FILE_PATHS['Partials']['HighLevel']['Configures'] . 'config_SubjectModule.php') ?>
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
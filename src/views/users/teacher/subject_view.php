<?php
session_start();
$CURRENT_PAGE = "dashboard";

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);

require_once(FILE_PATHS['Controllers']['User']);
require_once(FILE_PATHS['Controllers']['SubjectSection']);
require_once(FILE_PATHS['Controllers']['Subject']);
require_once(FILE_PATHS['Controllers']['Section']);
require_once(FILE_PATHS['Controllers']['ModuleContent']);

require_once(FILE_PATHS['Functions']['SessionChecker']);
require_once(FILE_PATHS['Functions']['ToastLogger']);
require_once(FILE_PATHS['Functions']['UpdateURLParams']);
require_once(FILE_PATHS['Functions']['PHPLogger']);

checkUserAccess(['Teacher', 'Student']);

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

$subjectSectionController = new SubjectSectionController($db);
$subjectController = new SubjectController();
$sectionController = new SectionController();
$moduleContentController = new ModuleContentController();

// Create an instance of the UserController
$userController = new UserController();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case "addSubjectModule":
                $moduleData = [
                    'subject_section_id' => $_GET['subject_section_id'],
                    'title' => $_POST['input_moduleName'],
                    'visibility' => $_POST['input_moduleVisibility'] ? "1" : "0"
                ];

                $_SESSION["_ResultMessage"] = $moduleContentController->addModule($moduleData);
                // Redirect to the same page to prevent resubmission
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            case "updateSubjectModule":
                $moduleData = [
                    'subject_section_id' => $_GET['subject_section_id'],
                    'title' => $_POST['input_moduleName'],
                    'visibility' => $_POST['input_moduleVisibility'] ? "1" : "0",
                    'module_id' => $_GET['module_id']
                ];
                $_SESSION["_ResultMessage"] = $moduleContentController->updateModule($moduleData);
                // Redirect to the same page to prevent resubmission
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            case "deleteSubjectModule":
                $moduleData = [
                    'module_id' => $_GET['module_id']
                ];
                $_SESSION["_ResultMessage"] = $moduleContentController->deleteModule($moduleData['module_id']);
                // Redirect to the same page to prevent resubmission
                header("Location: " . updateUrlParams(['subject_section_id' => $_GET['subject_section_id']]));
                exit();
            case "addModuleContent":
                $contentData = [
                    'module_id' => $_GET['module_id'],
                    'content_title' => $_POST['input_contentTitle'],
                    'content_type' => $_POST['input_contentType'],
                    'description' => $_POST['input_contentDescription'],
                    'visibility' => $_POST['input_contentVisibility'] ? "show" : "hide",
                    'start_date' => $_POST['input_contentStartDate'],
                    'due_date' => $_POST['input_contentDueDate'],
                    'max_attempts' => $_POST['input_unlimitedAttempts'] ? 9999 : $_POST['input_contentMaxAttemps'],
                    'assignment_type' => $_POST['input_contentAssignmentType'],
                    'allow_late' => $_POST['input_contentAllowLate'],
                    'max_score' => $_POST['input_contentMaxScore'],
                    'files' => $_FILES['input_contentFiles']
                ];

                $_SESSION["_ResultMessage"] = $moduleContentController->addContent($contentData);

                // Redirect to the same page to prevent resubmission
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            case "updateModuleContentVisibility":
                $contentData = [
                    "content_id" => $_POST['content_id'],
                    "module_id" => $_GET['module_id'],
                ];
                $_SESSION["_ResultMessage"] = $moduleContentController->updateContentVisibility($contentData);

                // Redirect to the same page to prevent resubmission
                header("Content-Type: application/json");
                echo json_encode($_SESSION["_ResultMessage"]);
                exit();
            case "deleteModuleContent":
                $contentData = [
                    "content_id" => $_POST['content_id'],
                    "module_id" => $_GET['module_id'],
                ];
                $_SESSION["_ResultMessage"] = $moduleContentController->deleteContent($contentData);

                // Redirect to the same page to prevent resubmission
                header("Content-Type: application/json");
                echo json_encode($_SESSION["_ResultMessage"]);
                exit();
        }
    }
}

if (!isset($_GET['subject_section_id'])) {
    header("Location: " . BASE_PATH_LINK);
    exit();
} else {
    // PRELOAD INFO
    $SUBJECT_SECTION_INFO = $subjectSectionController->getSubjectSectionDetails($_GET['subject_section_id']);
    if (!$SUBJECT_SECTION_INFO['success']) {
        $_SESSION["_ResultMessage"] = ['success' => false, 'message' => "No subject_section_id found with an id of (" . $_GET['subject_section_id'] . ")"];
        header("Location: " . clearUrlParams());
        exit();
    }

    $SUBJECT_INFO = $subjectController->getSubjectFromSubjectId($SUBJECT_SECTION_INFO['data']['subject_id']);
    $SECTION_INFO = $sectionController->getSectionById($SUBJECT_SECTION_INFO['data']['section_id']);

    $CONFIGURE_MODULE = false;
    $CONFIGURE_MODULE_CONTENT = false;
    if (isset($_GET['module_id'])) {
        $getModuleByModuleId = $moduleContentController->getModule($_GET['module_id']);
        if (!$getModuleByModuleId['success'] || $getModuleByModuleId['success'] && empty($getModuleByModuleId['data'])) {
            $_SESSION["_ResultMessage"] = ['success' => false, 'message' => "No module found with an id of (" . $_GET['module_id'] . ")"];
            // Redirect to the same page to prevent resubmission
            header("Location: " . updateUrlParams(['subject_section_id' => $_GET['subject_section_id']]));
            exit();
        }
        $CONFIGURE_MODULE = true;
        if (isset($_GET['content_id'], $_GET["configure"])) {
            $CONFIGURE_MODULE_CONTENT = true;
        }
    }
}

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
                                    <h5 class="text-success">
                                        <?php echo $SUBJECT_INFO['data']['subject_name'] . ' (' . $SECTION_INFO['data']['section_name'] . ')' ?>
                                    </h5>
                                </div>
                                <div class="col-lg-4 d-flex justify-content-end align-items-center">
                                    <?php if ($_SESSION['role'] == "Teacher" && !isset($_GET['module_id'])): ?>
                                        <button class="btn btn-success shadow-sm d-flex gap-2" data-bs-toggle="modal"
                                            data-bs-target="#modal_addModule">
                                            <i class="bi bi-plus-circle"></i>
                                            Add Module
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>
                            <?php if (!$CONFIGURE_MODULE): ?>
                                <section id="modules_container" class="d-flex flex-column gap-3 mb-3">

                                    <?php
                                    // Load All Modules from this Subject
                                    $getAllModules = $moduleContentController->getModules($_GET['subject_section_id']);
                                    if ($getAllModules['success']): ?>
                                        <?php if ($getAllModules['data']): ?>
                                            <?php foreach ($getAllModules['data'] as $module): ?>
                                                <div class="accordion rounded shadow-sm border overflow-hidden"
                                                    id="module_<?php echo $module['module_id'] ?>">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingModule1">
                                                            <button
                                                                class="accordion-button collapsed bg-success bg-opacity-75 text-white fs-5 fw-medium"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#collapseModule<?php echo $module['module_id'] ?>"
                                                                aria-expanded="false"
                                                                aria-controls="collapseModule<?php echo $module['module_id'] ?>">
                                                                <?php echo htmlspecialchars($module['title']) ?>
                                                            </button>
                                                        </h2>
                                                        <div id="collapseModule<?php echo $module['module_id'] ?>"
                                                            class="accordion-collapse collapse"
                                                            aria-labelledby="headingModule<?php echo $module['module_id'] ?>"
                                                            data-bs-parent="#module_<?php echo $module['module_id'] ?>">
                                                            <div class="accordion-body">
                                                                <?php if ($_SESSION['role'] == "Teacher"): ?>
                                                                    <div class="d-flex justify-content-end align-items-center gap-2 mb-3">
                                                                        <a
                                                                            href="<?php echo updateUrlParams(['subject_section_id' => $_GET['subject_section_id'], 'module_id' => $module['module_id']]) ?>">
                                                                            <button class="btn btn-sm btn-primary shadow-sm text-white"
                                                                                title="Edit this Module">
                                                                                <i class="bi bi-pencil-square"></i>
                                                                                Edit Module
                                                                            </button>
                                                                        </a>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <ul class="list-group list-group-flush">
                                                                    <?php
                                                                    // Get All contents of the module from this Subject.
                                                                    $getAllContentsFromModule = $moduleContentController->getContents($module['module_id']);

                                                                    if ($getAllContentsFromModule['success']): ?>
                                                                        <?php if ($getAllContentsFromModule['data']): ?>
                                                                            <?php foreach ($getAllContentsFromModule['data'] as $content):
                                                                                $canShow = ($_SESSION['role'] == 'Teacher' || $_SESSION['role'] == 'Student' && $content['visibility'] == 'show');
                                                                                if ($canShow):
                                                                            ?>
                                                                                    <li class="list-group-item d-flex gap-2">
                                                                                        <?php
                                                                                        $contentLink = null;
                                                                                        $isFile = false;
                                                                                        $contentIconClass = "bi-asterisk text-dark";
                                                                                        $titleHint = "null";
                                                                                        switch ($content['content_type']) {
                                                                                            case 'handout':
                                                                                                $contentIconClass = "bi-file-earmark-text-fill text-critical";
                                                                                                $titleHint = "Handout";
                                                                                                $contentLink = BASE_PATH_LINK . "src/models/DownloadFile.php?content_id=" . $content['content_id'];
                                                                                                $isFile = true;
                                                                                                break;
                                                                                            case 'assignment':
                                                                                                $contentIconClass = "bi-clipboard-fill text-primary";
                                                                                                $contentLink = "#";
                                                                                                $titleHint = "Assignment";
                                                                                                break;
                                                                                            case 'quiz':
                                                                                                $contentIconClass = "bi-stickies-fill text-warning";
                                                                                                $contentLink = "#";
                                                                                                $titleHint = "Quiz";
                                                                                                break;
                                                                                        }

                                                                                        ?>
                                                                                        <div>
                                                                                            <i class="bi <?php echo $contentIconClass ?>"
                                                                                                title="<?php echo $titleHint ?>"></i>
                                                                                            <a href="<?php echo $contentLink ?>" <?php echo ($isFile) ? 'target="_blank"' : '' ?>
                                                                                                class="link-body-emphasis"><?php echo htmlspecialchars($content['content_title']) ?></a>
                                                                                        </div>
                                                                                    </li>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                        <?php else: ?>
                                                                            <h6 class="p-2 text-center">No Contents</h6>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                </ul>
                                                                <?php if ($_SESSION['role'] == "Teacher"): ?>
                                                                    <div class="p-3 text-end border-top">
                                                                        <span>Visible to students:
                                                                            <strong><?php echo $module['visibility'] == '1' ? "Yes" : "No"; ?></strong></span>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <h6 class="text-center">No modules yet added.</h6>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </section>
                            <?php else: ?>
                                <section id="module_editor_view" class="p-2 mb-3">
                                    <div>
                                        <a
                                            href="<?php echo updateUrlParams(["subject_section_id" => $_GET['subject_section_id']]) ?>">
                                            <button class="btn btn-sm btn-transparent text-success">
                                                <i class="bi bi-arrow-bar-left"></i>
                                                Go Back
                                            </button>
                                        </a>
                                    </div>
                                    <div class="fs-5 text-center mb-3">Module Information</div>
                                    <form id="formModuleInformation" method="POST">
                                        <input type="hidden" name="action" value="updateSubjectModule" id="inputAction">
                                        <div class="row">
                                            <div class="row col-md-12">
                                                <div class="col-md-6">
                                                    <label for="input_moduleName">Module Name</label>
                                                    <input type="text" name="input_moduleName" id="input_moduleName"
                                                        class="form-control"
                                                        value="<?php echo $getModuleByModuleId['data'][0]['title'] ?>">
                                                </div>
                                                <div class="col-md-6 d-flex align-items-end">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            id="input_moduleVisibility" name="input_moduleVisibility" <?php echo $getModuleByModuleId['data'][0]['visibility'] == '1' ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="input_moduleVisibility">
                                                            Module Visibility
                                                            <sup class="opacity-75"
                                                                title="Indicates the visibity of the module to Student's point of view.">
                                                                <i class="bi bi-info-circle-fill fs-7"></i>
                                                            </sup>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 d-flex justify-content-start px-3 mt-5 gap-2">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-floppy-fill"></i>
                                                    Save
                                                </button>
                                                <span class="btn btn-sm btn-danger" id="btnDelete" data-bs-toggle="modal"
                                                    data-bs-target="#deleteConfirmationModal">
                                                    <i class="bi bi-trash-fill"></i>
                                                    Delete Module
                                                </span>
                                                <script>
                                                    $(document).ready(function() {
                                                        $("#confirmDeleteBtn").on('click', function() {
                                                            $("#inputAction").val("deleteSubjectModule");
                                                            $("#formModuleInformation").submit();
                                                        });
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                    </form>
                                    <div id="confirmation_modal">
                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1"
                                            aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm
                                                            Deletion</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete this module? Deletion of this module
                                                        will delete all of its content and its connected
                                                        files including submissions from other users. This action cannot be
                                                        undone.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-danger"
                                                            id="confirmDeleteBtn">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="addContent_modal">
                                        <!-- Add Module Content Modal -->
                                        <div class="modal fade" id="addModuleContentModal" tabindex="-1"
                                            aria-labelledby="addModuleContentLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addModuleContentLabel">Add Module
                                                            Content</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form id="moduleContentForm" method="POST"
                                                        enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="action" value="addModuleContent">
                                                            <!-- Content Title and Content Type (Same Row) -->
                                                            <div class="row g-3 mb-3">
                                                                <div class="col-md-6">
                                                                    <label for="contentType" class="form-label">Content
                                                                        Type</label>
                                                                    <select class="form-select" id="contentType"
                                                                        name="input_contentType" required>
                                                                        <option value="handout">Handout</option>
                                                                        <option value="assignment">Assignment</option>
                                                                        <option value="quiz">Quiz</option>
                                                                    </select>
                                                                    <div class="invalid-feedback"></div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="contentTitle" class="form-label">Content
                                                                        Title</label>
                                                                    <input type="text" class="form-control"
                                                                        id="contentTitle" name="input_contentTitle"
                                                                        placeholder="" required>
                                                                    <div class="invalid-feedback"></div>
                                                                </div>
                                                            </div>

                                                            <!-- Description -->
                                                            <div class="mb-3" id="descriptionContainer">
                                                                <label for="description"
                                                                    class="form-label">Description</label>
                                                                <textarea class="tinyMCE" id="description" rows="3"
                                                                    name="input_contentDescription"
                                                                    placeholder="Enter description"></textarea>
                                                            </div>

                                                            <!-- File Input for Handout -->
                                                            <div class="mb-3 d-none" id="fileInputContainer">
                                                                <label for="fileInput" class="form-label">Upload
                                                                    Files</label>
                                                                <input type="file" class="form-control" id="fileInput"
                                                                    name="input_contentFiles[]" multiple>
                                                            </div>

                                                            <!-- Visibility -->
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <!-- Max Attempts -->
                                                                    <div class="mb-3" id="maxAttemptsContainer">
                                                                        <label for="maxAttempts" class="form-label">Max
                                                                            Attempts</label>
                                                                        <input type="number" class="form-control"
                                                                            id="maxAttempts" name="input_contentMaxAttempts"
                                                                            min="1" placeholder="Enter max attempts"
                                                                            value="1">
                                                                        <div class="invalid-feedback"></div>
                                                                        <div class="form-check mt-2">
                                                                            <input class="form-check-input" type="checkbox"
                                                                                id="unlimitedAttempts"
                                                                                name="input_unlimitedAttempts">
                                                                            <label class="form-check-label"
                                                                                for="unlimitedAttempts">Unlimited</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <!-- Assignment Type -->
                                                                    <div class="mb-3" id="assignmentTypeContainer">
                                                                        <label for="assignmentType"
                                                                            class="form-label">Assignment Type</label>
                                                                        <select class="form-select" id="assignmentType"
                                                                            name="input_contentAssignmentType">
                                                                            <option value="dropbox">Dropbox</option>
                                                                            <option value="richText">Rich Text</option>
                                                                            <option value="both">Both</option>
                                                                        </select>
                                                                        <div class="invalid-feedback"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <!-- Assignment Type -->
                                                                    <div class="mb-3" id="maxScoreContainer">
                                                                        <label for="maxScoreContainer"
                                                                            class="form-label">Max Score</label>
                                                                        <input class="form-control" type="number"
                                                                            name="input_contentMaxScore"
                                                                            id="maxScoreContainer" min="1" value="100"
                                                                            placeholder="either 100">
                                                                        <div class="invalid-feedback"></div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Start Date and Due Date (Same Row) -->
                                                            <div class="row g-3 mb-3" id="dateContainer">
                                                                <div class="col-md-6">
                                                                    <label for="startDate" class="form-label">Start
                                                                        Date</label>
                                                                    <input type="datetime-local" class="form-control"
                                                                        id="startDate" name="input_contentStartDate">
                                                                    <div class="invalid-feedback"></div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="dueDate" class="form-label">Due Date</label>
                                                                    <input type="datetime-local" class="form-control"
                                                                        id="dueDate" name="input_contentDueDate">
                                                                    <div class="invalid-feedback"></div>
                                                                </div>
                                                            </div>

                                                            <!-- Allow Late -->
                                                            <div class="form-check form-switch mb-3"
                                                                id="allowLateContainer">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="allowLate" name="input_contentAllowLate">
                                                                <label class="form-check-label" for="allowLate">Allow
                                                                    Late</label>
                                                                <div class="invalid-feedback"></div>
                                                            </div>

                                                            <div class="form-check form-switch mb-3"
                                                                id="visibilityContainer">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="visibility" name="input_contentVisibility">
                                                                <label class="form-check-label"
                                                                    for="visibility">Visibility</label>
                                                                <div class="invalid-feedback"></div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <span type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</span>
                                                            <button type="submit" class="btn btn-success">Save
                                                                Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            $(document).ready(function() {
                                                // Function to toggle fields based on Content Type selection
                                                function toggleFields() {
                                                    const selectedType = $('#contentType').val();

                                                    // Reset required attributes and validation styles
                                                    $('#addModuleContentModal [required]').removeAttr('required').removeClass('is-invalid');

                                                    // Show/hide fields and set required attributes
                                                    if (selectedType === 'handout') {
                                                        $('#descriptionContainer').removeClass('d-none').find('textarea').attr('required', false);
                                                        $('#fileInputContainer').removeClass('d-none').find('input[type="file"]').attr('required', true);
                                                        $('#visibilityContainer').removeClass('d-none');
                                                        $('#dateContainer').addClass('d-none');
                                                        $('#maxAttemptsContainer').addClass('d-none');
                                                        $('#assignmentTypeContainer').addClass('d-none');
                                                        $('#allowLateContainer').addClass('d-none');
                                                        $('#maxScoreContainer').addClass('d-none'); // Hide for handout
                                                    } else if (selectedType === 'assignment') {
                                                        $('#descriptionContainer').removeClass('d-none').find('textarea').attr('required', false);
                                                        $('#dateContainer').removeClass('d-none').find('input').attr('required', true);
                                                        $('#maxAttemptsContainer').removeClass('d-none').find('input[type="number"]').attr('required', true);
                                                        $('#assignmentTypeContainer').removeClass('d-none').find('select').attr('required', true);
                                                        $('#allowLateContainer').removeClass('d-none');
                                                        $('#visibilityContainer').removeClass('d-none');
                                                        $('#maxScoreContainer').removeClass('d-none').find('input').attr('required', true); // Show for assignment

                                                        $('#fileInputContainer').addClass('d-none');
                                                    } else {
                                                        $('#descriptionContainer').addClass('d-none').find('textarea').attr('required', false);
                                                        $('#dateContainer').removeClass('d-none').find('input').attr('required', true);
                                                        $('#visibilityContainer').removeClass('d-none');
                                                        $('#maxScoreContainer').addClass('d-none'); // Hide for other types
                                                        $('#allowLateContainer').removeClass('d-none');
                                                        $('#maxAttemptsContainer').removeClass('d-none');

                                                        $('#fileInputContainer').addClass('d-none').find('input[type="file"]').attr('required', false);
                                                        $('#assignmentTypeContainer').addClass('d-none');
                                                    }
                                                }

                                                // Function to validate Start Date and Due Date
                                                function validateDates() {
                                                    const now = new Date();
                                                    const startDate = new Date($('#startDate').val());
                                                    const dueDate = new Date($('#dueDate').val());
                                                    let isValid = true;

                                                    // Start Date Validation
                                                    if (startDate < now) {
                                                        $('#startDate').addClass('is-invalid').siblings('.invalid-feedback').text('Start Date must be today or later.');
                                                        isValid = false;
                                                    } else {
                                                        $('#startDate').removeClass('is-invalid');
                                                    }

                                                    // Due Date Validation
                                                    if (dueDate <= startDate) {
                                                        $('#dueDate').addClass('is-invalid').siblings('.invalid-feedback').text('Due Date must be after Start Date and time.');
                                                        isValid = false;
                                                    } else {
                                                        $('#dueDate').removeClass('is-invalid');
                                                    }

                                                    return isValid;
                                                }

                                                // Function to validate required fields
                                                function validateRequiredFields() {
                                                    let isValid = true;
                                                    $('#addModuleContentModal [required]').each(function() {
                                                        if (!$(this).val()) {
                                                            $(this).addClass('is-invalid').siblings('.invalid-feedback').text('This field is required.');
                                                            isValid = false;
                                                        } else {
                                                            $(this).removeClass('is-invalid');
                                                        }
                                                    });
                                                    return isValid;
                                                }

                                                // Bind to Content Type change event
                                                $('#contentType').on('change', toggleFields);

                                                // Validate dates and required fields before form submission
                                                $('#addModuleContentForm').on('submit', function(e) {
                                                    const isDatesValid = validateDates();
                                                    const areFieldsValid = validateRequiredFields();

                                                    if (!isDatesValid || !areFieldsValid) {
                                                        e.preventDefault(); // Prevent form submission if validation fails
                                                    }
                                                });

                                                // Trigger the change event on modal open to ensure correct state
                                                $('#addModuleContentModal').on('show.bs.modal', function() {
                                                    $('#contentType').trigger('change');
                                                });
                                            });
                                        </script>
                                    </div>
                                    <section class="mt-5">
                                        <div class="row mb-2">
                                            <div class="col-md-6">Contents</div>
                                            <div class="col-md-6 d-flex justify-content-end align-items-center">
                                                <button class="btn btn-sm btn-success d-flex align-items-center gap-2"
                                                    data-bs-toggle="modal" data-bs-target="#addModuleContentModal">
                                                    <i class="bi bi-plus-circle"></i>
                                                    Add Content
                                                </button>
                                            </div>
                                        </div>
                                        <section id="module_contents" class="border">
                                            <ul class="list-group list-group-flush overflow-hidden">
                                                <?php
                                                $getAllContentsFromModule = $moduleContentController->getContents($getModuleByModuleId['data'][0]['module_id']);
                                                if ($getAllContentsFromModule['success']):
                                                    if ($getAllContentsFromModule['data']):
                                                        foreach ($getAllContentsFromModule['data'] as $content):
                                                            $contentIconClass = "bi-asterisk text-dark";
                                                            $titleHint = "null";
                                                            $contentVisibilityIcon = $content['visibility'] == "show" ? "bi-eye-fill" : "bi-eye-slash";
                                                            switch ($content['content_type']) {
                                                                case 'handout':
                                                                    $contentIconClass = "bi-file-earmark-text-fill text-critical";
                                                                    $titleHint = "Handout";
                                                                    break;
                                                                case 'assignment':
                                                                    $contentIconClass = "bi-clipboard-fill text-primary";
                                                                    $titleHint = "Assignment";
                                                                    break;
                                                                case 'quiz':
                                                                    $contentIconClass = "bi-stickies-fill text-warning";
                                                                    $titleHint = "Quiz";
                                                                    break;
                                                            }
                                                            // Get content's file
                                                            $getAllContentFilesFromContent = $moduleContentController->getContentFile($content['content_id']);
                                                ?>
                                                            <li class="list-group-item row d-flex align-items-center">
                                                                <div class="col-md-8">
                                                                    <div class="d-flex justify-content-start align-items-center gap-2">
                                                                        <i class="bi <?php echo $contentIconClass ?>"
                                                                            title="<?php echo $titleHint ?>"></i>
                                                                        <span
                                                                            class="link-body-emphasis"><?php echo htmlspecialchars($content['content_title']) ?></span>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="col-md-4 bg-transparent d-flex justify-content-end align-items-center gap-2">
                                                                    <button id="<?php echo $content['content_id'] ?>"
                                                                        class="contentButton_ToggleVisibity btn btn-sm btn-transparent text-primary"
                                                                        title="visibility">
                                                                        <i class="fs-6 bi <?php echo $contentVisibilityIcon ?>"></i>
                                                                    </button>
                                                                    <button id="<?php echo $content['content_id'] ?>"
                                                                        class="contentButton_ConfigContent btn btn-sm btn-transparent text-success"
                                                                        title="edit">
                                                                        <i class="fs-6 bi bi-pencil-square"></i>
                                                                    </button>
                                                                    <button id="<?php echo $content['content_id'] ?>"
                                                                        class="contentButton_Delete btn btn-sm btn-transparent text-danger"
                                                                        title="delete" data-bs-toggle="modal"
                                                                        data-bs-target="#deleteConfirmationModalForContent">
                                                                        <i class="fs-6 bi bi-trash-fill"></i>
                                                                    </button>
                                                                </div>
                                                            </li>
                                                        <?php endforeach; ?>
                                                        <script>
                                                            $(document).ready(function() {
                                                                $(".contentButton_ToggleVisibity").on("click", function(e) {
                                                                    $.ajax({
                                                                        url: "",
                                                                        type: "POST",
                                                                        data: {
                                                                            action: "updateModuleContentVisibility",
                                                                            content_id: $(this).attr("id"),
                                                                        }, // Data to send to the server
                                                                        success: function(response) {
                                                                            // console.log(response); // Handle success
                                                                            location.reload();
                                                                        },
                                                                        error: function(xhr, status, error) {
                                                                            console.error(error); // Handle error
                                                                        }
                                                                    });
                                                                });
                                                                $(".contentButton_ConfigContent").on("click", function() {
                                                                    console.log("config");
                                                                });
                                                                $(".contentButton_Delete").on("click", function() {
                                                                    const this_content_id = $(this).attr("id");
                                                                    $("#confirmDeleteContentBtn").on("click", function() {
                                                                        $.ajax({
                                                                            url: "",
                                                                            type: "POST",
                                                                            data: {
                                                                                action: "deleteModuleContent",
                                                                                content_id: this_content_id,
                                                                            }, // Data to send to the server
                                                                            success: function(response) {
                                                                                // console.log(response); // Handle success
                                                                                location.reload();
                                                                            },
                                                                            error: function(xhr, status, error) {
                                                                                console.error(error); // Handle error
                                                                            }
                                                                        });
                                                                    });
                                                                });
                                                            });
                                                        </script>
                                                    <?php else: ?>
                                                        <li
                                                            class="list-group-item row d-flex align-items-center justify-content-center">
                                                            No contents...
                                                        </li>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <li
                                                        class="list-group-item row d-flex align-items-center justify-content-center">
                                                        Something went wrong...
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </section>
                                    </section>
                                </section>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 mt-md-2 mt-lg-0 px-sm-0 px-md-1 d-flex flex-column gap-1">
                    <?php require_once(FILE_PATHS['Partials']['User']['Tasks']) ?>
                    <?php require_once(FILE_PATHS['Partials']['User']['Announcements']) ?>
                </div>
            </section>
        </section>

        <!-- ADD Module Modal -->
        <div class="modal fade" id="modal_addModule" tabindex="-1" aria-labelledby="modal_addModule" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5 text-start">
                            Add Module Form
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Form inside the modal -->
                        <form method="POST">
                            <input type="hidden" name="action" value="addSubjectModule">
                            <div class="mb-3">
                                <label for="input_moduleName" class="form-label">Module Name</label>
                                <input type="text" class="form-control px-3 py-2" id="input_moduleName"
                                    name="input_moduleName" placeholder="" required />
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        id="input_moduleVisibility" name="input_moduleVisibility">
                                    <label class="form-check-label" for="input_moduleVisibility">Module
                                        Visibility</label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <input type="submit" value="Submit" class="btn btn-success">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal for Content -->
        <div class="modal fade" id="deleteConfirmationModalForContent" tabindex="-1"
            aria-labelledby="deleteConfirmationModalForContentLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmationModalForContent">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this content? Deletion of this content will delete all of its
                        data and its connected
                        files including submissions from other users. This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteContentBtn">Delete</button>
                    </div>
                </div>
            </div>
        </div>

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
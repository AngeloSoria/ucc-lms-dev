<?php

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

require_once UTILS;

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
                    'visibility' => $_POST['input_moduleVisibility'] ? "shown" : "hidden"
                ];

                $_SESSION["_ResultMessage"] = $moduleContentController->addModule($moduleData);
                // Redirect to the same page to prevent resubmission
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            case "updateSubjectModule":
                $moduleData = [
                    'subject_section_id' => $_GET['subject_section_id'],
                    'title' => $_POST['input_moduleName'],
                    'visibility' => $_POST['input_moduleVisibility'] ? "shown" : "hidden",
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
                    'visibility' => $_POST['input_contentVisibility'] ? "shown" : "hidden",
                    'start_date' => $_POST['input_contentStartDate'],
                    'due_date' => $_POST['input_contentDueDate'],
                    'max_attempts' => $_POST['input_unlimitedAttempts'] ? 9999 : $_POST['input_contentMaxAttempts'],
                    'assignment_type' => $_POST['input_contentAssignmentType'],
                    'allow_late' => $_POST['input_contentAllowLate'] ? 1 : 0,
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
            case "addContentSubmission":
                //Prevent other role except Student from submitting.
                if (!userHasPerms(['Student'])) {
                    $_SESSION['_ResultMessage'] = ['success' => false, 'message' => 'Only students can submit.'];
                    // Redirect to the same page to prevent resubmission
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit();
                }

                $submissionData = [
                    "content_id" => $_GET['content_id'],
                    "student_id" => $_SESSION['user_id'],
                    "submission_text" => $_POST['submission_Text'],
                ];

                // echo json_encode($submissionData);

                $_SESSION["_ResultMessage"] = $moduleContentController->addSubmission($submissionData, $_FILES['submission_Files']);

                // Redirect to the same page to prevent resubmission
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            case "setSubmissionGrade":
                //Prevent other role except Student from submitting.
                if (!userHasPerms(['Teacher'])) {
                    $_SESSION['_ResultMessage'] = ['success' => false, 'message' => 'You don\'t have perms to do this action.'];
                    // Redirect to the same page to prevent resubmission
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit();
                }

                $submissionData = [
                    "content_id" => $_GET['content_id'],
                    "submission_id" => $_POST['submission_id'],
                    "status" => "graded",
                    "student_id" => $_GET['student_id'],
                    "score" => $_POST['input_submissionScore'] == null ? 0 : $_POST['input_submissionScore'],
                ];

                msgLog('DATA', json_encode($submissionData));

                // echo json_encode($submissionData);

                $_SESSION["_ResultMessage"] = $moduleContentController->updateSubmissionGrade($submissionData);

                // Redirect to the same page to prevent resubmission
                header("Location: " . $_SERVER['REQUEST_URI']);
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
        if (isset($_GET['content_id'])) {
            $CONFIGURE_MODULE = false;
        }
    }
}

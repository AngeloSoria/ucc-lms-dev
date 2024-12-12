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

require_once CONTROLLERS . 'QuizController.php';
require_once CONTROLLERS . 'AnnouncementsController.php';



require_once UTILS;

checkUserAccess(['Teacher', 'Student']);

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection(); // Establish the database connection

$subjectSectionController = new SubjectSectionController($db);
$subjectController = new SubjectController();
$sectionController = new SectionController();
$moduleContentController = new ModuleContentController();
$quizController = new QuizController();
$announcementController = new AnnouncementController();

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
                    "score" => $_POST['input_submissionScore'] == null ? 0 : $_POST['input_submissionScore'],
                    "status" => "graded",
                    "content_id" => $_GET['content_id'],
                    "student_id" => $_GET['student_id'],
                    "submission_id" => $_POST['submission_id'],
                ];

                msgLog('DATA', json_encode($submissionData));

                $_SESSION["_ResultMessage"] = $moduleContentController->updateSubmissionGrade($submissionData);

                // Redirect to the same page to prevent resubmission
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            case "addNewQuestion":

                // Gather and sanitize POST data
                $content_id = isset($_GET['content_id']) ? $_GET['content_id'] : null;

                $question_text = isset($_POST['question_text']) ? trim($_POST['question_text']) : '';
                $question_type = isset($_POST['question_type']) ? $_POST['question_type'] : '';
                $question_points = isset($_POST['question_points']) ? $_POST['question_points'] : '';

                // Validation for required fields
                if (empty($question_text) || empty($question_type) || empty($question_points)) {
                    $error_message = "Please fill in all required fields.";
                }

                if (empty($error_message)) {
                    // Insert question into the database
                    $stmt = $db->prepare("INSERT INTO quiz_questions (content_id, question_type, question_text, question_points) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$content_id, $question_type, $question_text, $question_points]);

                    $question_id = $db->lastInsertId();

                    // Handle Multiple Choice Questions (MCQ)
                    if ($question_type === 'MCQ') {
                        $choicesContext = isset($_POST['choices']) ? $_POST['choices'] : [];
                        $choicesCorrectValues = isset($_POST['mcq_item']) ? $_POST['mcq_item'] : [];

                        if (count($choicesContext) < 2) {
                            $error_message = "Please provide at least 2 choices for MCQ.";
                        } else {
                            foreach ($choicesContext as $index => $itemContext) {
                                $is_correct = ($index == (int) $choicesCorrectValues[0]) ? 1 : 0;
                                $stmt = $db->prepare("INSERT INTO quiz_question_options (quiz_question_id, option_text, is_correct) VALUES (?, ?, ?)");
                                $stmt->execute([$question_id, $itemContext, $is_correct]);
                            }
                        }
                    }

                    // Handle True/False Questions
                    if ($question_type === 'TRUE_FALSE') {
                        // Get the selected answer
                        $correct_answer = isset($_POST['tf_choice']) ? $_POST['tf_choice'] : null;

                        // Ensure the correct answer is selected
                        if (isset($correct_answer) && ($correct_answer === 'TRUE' || $correct_answer === 'FALSE')) {

                            // Insert the correct answer for TRUE
                            $is_correct_true = ($correct_answer === 'TRUE') ? 1 : 0;
                            $stmt = $db->prepare("INSERT INTO quiz_question_options (quiz_question_id, option_text, is_correct) VALUES (?, 'TRUE', ?)");
                            $stmt->execute([$question_id, $is_correct_true]);

                            // Insert the correct answer for FALSE
                            $is_correct_false = ($correct_answer === 'FALSE') ? 1 : 0;
                            $stmt = $db->prepare("INSERT INTO quiz_question_options (quiz_question_id, option_text, is_correct) VALUES (?, 'FALSE', ?)");
                            $stmt->execute([$question_id, $is_correct_false]);
                        } else {
                            $error_message = "Please select either TRUE or FALSE.";
                        }
                    }

                    // Handle Fill in the Blank Questions
                    if ($question_type === 'FILL_IN_THE_BLANKS') {
                        $correct_answer = isset($_POST['correct_answer']) ? $_POST['correct_answer'] : '';
                        if (empty($correct_answer)) {
                            $error_message = "Please provide the correct answer for the fill-in-the-blank question.";
                        } else {
                            $stmt = $db->prepare("INSERT INTO quiz_question_options (quiz_question_id, option_text, is_correct) VALUES (?, ?, ?)");
                            $stmt->execute([$question_id, $correct_answer, 1]); // Assuming the answer is always correct
                        }
                    }
                }

                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();

            case 'deleteQuestion':

                if (isset($_POST['quiz_question_id'])) {
                    $quiz_question_id = $_POST['quiz_question_id'];

                    // Call the deleteQuestion method
                    $_SESSION["_ResultMessage"] = $quizController->deleteQuestion($quiz_question_id);

                    // Redirect to the same page after deletion
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit();
                }
            case 'addAnnouncement_subjectsection':
                //Prevent other role except Student from submitting.
                if (!userHasPerms(['Teacher'])) {
                    $_SESSION['_ResultMessage'] = ['success' => false, 'message' => 'You don\'t have perms to do this action.'];
                    // Redirect to the same page to prevent resubmission
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit();
                }

                $announcementData = [
                    'announcer_id' => $_SESSION['user_id'],
                    'subject_section_id' => $_GET['subject_section_id'],
                    'title' => $_POST['input_announcementTitle'],
                    'message' => $_POST['input_announcementMessage'],
                ];

                $_SESSION["_ResultMessage"] = $announcementController->addAnnouncement($announcementData);

                // Redirect to the same page after deletion
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            case "deleteAnnouncement_subjectsection":
                //Prevent other role except Student from submitting.
                if (!userHasPerms(['Teacher'])) {
                    $_SESSION['_ResultMessage'] = ['success' => false, 'message' => 'You don\'t have perms to do this action.'];
                    // Redirect to the same page to prevent resubmission
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit();
                }

                $announcement_id = $_POST['announcement_id'];
                if (!isset($announcement_id)) {
                    $_SESSION['_ResultMessage'] = ['success' => false, 'message' => 'No announcement id passed.'];
                    // Redirect to the same page to prevent resubmission
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit();
                }

                $_SESSION["_ResultMessage"] = $announcementController->deleteAnnouncement($announcement_id);
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

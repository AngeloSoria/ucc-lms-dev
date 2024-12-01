<?php
require_once(__DIR__ . "../../../src/config/PathsHandler.php");
require_once(FILE_PATHS["Models"]["Subject"]);
require_once(FILE_PATHS["Functions"]["PHPLogger"]);
require_once(FILE_PATHS["Controllers"]["GeneralLogs"]);

class SubjectController
{
    private $subjectModel;
    private $generalLogsController;

    public function __construct()
    {
        $this->subjectModel = new Subject();
        $this->generalLogsController = new GeneralLogsController();
    }

    public function addSubject($subjectData)
    {
        // Check if the subject already exists
        if ($this->subjectModel->checkSubjectExist($subjectData["subject_code"], $subjectData["subject_name"])) {
            return [
                "success" => false,
                "message" => "Subject with code (" . $subjectData["subject_code"] . ") and name (" . $subjectData["subject_name"] . ") already exists."
            ];
        }

        // Add the subject using the model
        $MODEL_RESULT = $this->subjectModel->addSubject($subjectData);

        // If subject creation was successful
        if ($MODEL_RESULT["success"] === true) {
            // Log the addition
            // msgLog("CRUD", "[ADD] [SUBJECT] [CODE: " . $subjectData["subject_code"] . "] [NAME: " . $subjectData["subject_name"] . "] | [" . $_SESSION["username"] . "] [" . $_SESSION["role"] . "]");

            // Insert to general_logs table.
            $this->generalLogsController->addLog_CREATE($_SESSION['user_id'], $_SESSION['role'], "Successfully created a subject named " . $subjectData["subject_name"]);

            return [
                "success" => true,
                "message" => "Subject added successfully."
            ];
        } else {
            // If there was an error in the model
            return [
                "success" => false,
                "message" => "Something went wrong adding subject. (" . $MODEL_RESULT["message"] . ")"
            ];
        }
    }

    public function getAllSubjects()
    {
        try {
            $queryResult = $this->subjectModel->getAllSubjects();
            if (!empty($queryResult)) {
                return ["success" => true, "data" => $queryResult['data']];
            } else {
                return ["success" => false, "message" => "No subjects has been retrieved."];
            }
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function getSubjectFromSubjectId($subject_id)
    {
        try {
            $result = $this->subjectModel->getSubjectFromSubjectId($subject_id);
            if ($result) {
                return ['success' => true, 'data' => $result['data']];
            } else {
                return ['success' => false, 'message' => "No subject found with subject_id of ($subject_id)."];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function deleteSubject($subject_data)
    {
        try {
            $MODEL_RESULT = $this->subjectModel->deleteSubject($subject_data['subject_id']);

            if ($MODEL_RESULT["success"] === true) {
                // msgLog("CRUD", "[DELETE] [SUBJECT] [ID: $subject_id] | [" . $_SESSION["username"] . "] [" . $_SESSION["role"] . "]");

                // Insert to general_logs table.
                $this->generalLogsController->addLog_DELETE($_SESSION['user_id'], $_SESSION['role'], "Deleted a subject named " . $subject_data['subject_name']);

                return [
                    "success" => true,
                    "message" => "Subject deleted successfully."
                ];
            } else {
                return [
                    "success" => false,
                    "message" => "Failed to delete subject. (" . $MODEL_RESULT["message"] . ")"
                ];
            }
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function updateSubject($subject_id, $subjectData)
    {
        try {
            $MODEL_RESULT = $this->subjectModel->updateSubject($subject_id, $subjectData);

            if ($MODEL_RESULT["success"] === true) {
                msgLog("CRUD", "[UPDATE] [SUBJECT] [ID: $subject_id] [NEW CODE: " . $subjectData["subject_code"] . "] [NEW NAME: " . $subjectData["subject_name"] . "] | [" . $_SESSION["username"] . "] [" . $_SESSION["role"] . "]");

                // Insert to general_logs table.
                $this->generalLogsController->addLog_UPDATE($_SESSION['user_id'], $_SESSION['role'], "Updated a subject from subject_id of " . $subject_id);

                return [
                    "success" => true,
                    "message" => "Subject updated successfully."
                ];
            } else {
                return [
                    "success" => false,
                    "message" => "Failed to update subject. (" . $MODEL_RESULT["message"] . ")"
                ];
            }
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
}

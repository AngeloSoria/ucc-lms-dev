<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Models']['Subject']);
require_once(FILE_PATHS['Functions']['PHPLogger']);
class SubjectController
{
    private $subjectModel;

    public function __construct($db)
    {
        $this->subjectModel = new Subject($db);
    }

    public function addSubject($subjectData)
    {
        // Check if the subject already exists
        if ($this->subjectModel->checkSubjectExist($subjectData['subject_code'], $subjectData['subject_name'])) {
            return [
                "success" => false,
                "message" => "Subject with code (" . $subjectData['subject_code'] . ") and name (" . $subjectData['subject_name'] . ") already exists."
            ];
        }

        // Add the subject using the model
        $MODEL_RESULT = $this->subjectModel->addSubject($subjectData);

        // If subject creation was successful
        if ($MODEL_RESULT['success'] === true) {
            // Log the addition
            msgLog("CRUD", "[ADD] [SUBJECT] [CODE: " . $subjectData["subject_code"] . "] [NAME: " . $subjectData["subject_name"] . "] | [" . $_SESSION["username"] . "] [" . $_SESSION["role"] . "]");

            return [
                "success" => true,
                "message" => "Subject added successfully."
            ];
        } else {
            // If there was an error in the model
            return [
                "success" => false,
                "message" => "Something went wrong adding subject. (" . $MODEL_RESULT['message'] . ")"
            ];
        }
    }
}

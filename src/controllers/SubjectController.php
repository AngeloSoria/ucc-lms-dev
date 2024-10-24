<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Models']['Subject']);

class SubjectController
{
    private $db;
    private $subjectModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->subjectModel = new User($this->db);
    }

    public function addSubject()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and assign user data
            $subjectData = [
                'subject_code' => htmlspecialchars(strip_tags($_POST['subject_id'])),
                'subject_name' => htmlspecialchars(strip_tags($_POST['subject_name'])),
                'course_id' => htmlspecialchars(strip_tags($_POST['course_id'])),
            ];
        }

        // Call the model to add the user
        if ($this->subjectModel->addUser($subjectData)) {
            //echo "User added successfully.";
        } else {
            //echo "Failed to add user.";
        }
    }
}

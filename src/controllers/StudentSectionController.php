<?
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Models']['User']);
require_once(FILE_PATHS['Functions']['PHPLogger']);
class StudentSectionController
{
    private $studentSectionModel;

    public function __construct($studentSectionModel)
    {
        $this->studentSectionModel = $studentSectionModel;
    }

    public function addStudentToSection($data)
    {
        // Validate required fields
        if (empty($data['student_id']) || empty($data['section_id']) || empty($data['enrollment_type'])) {
            return [
                "success" => false,
                "message" => "All fields are required: student_id, section_id, and enrollment_type."
            ];
        }

        // Check if the student is already in the section
        if ($this->studentSectionModel->checkStudentInSection($data['student_id'], $data['section_id'])) {
            return [
                "success" => false,
                "message" => "Student is already enrolled in this section."
            ];
        }

        // Add student to section
        $MODEL_RESULT = $this->studentSectionModel->addStudentToSection($data);

        if ($MODEL_RESULT['success']) {
            msgLog("CRUD", "[ADD] [STUDENT_SECTION] [STUDENT_ID: " . $data["student_id"] . "] [SECTION_ID: " . $data["section_id"] . "] | [" . $_SESSION["username"] . "] [" . $_SESSION["role"] . "]");
            return [
                "success" => true,
                "message" => "Student successfully added to the section."
            ];
        } else {
            return [
                "success" => false,
                "message" => "Error adding student to section. (" . $MODEL_RESULT['message'] . ")"
            ];
        }
    }
}

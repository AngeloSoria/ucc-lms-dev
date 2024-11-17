<? class StudentEnrollmentController
{
    private $studentEnrollmentModel;

    public function __construct($studentEnrollmentModel)
    {
        $this->studentEnrollmentModel = $studentEnrollmentModel;
    }

    // Add student to enrollment
    public function enrollStudent($data)
    {
        // Validate required fields
        if (empty($data['user_id']) || empty($data['subject_section_id']) || empty($data['enrollment_status']) || empty($data['enrollment_type']) || empty($data['period_id'])) {
            return [
                "success" => false,
                "message" => "All fields are required: user_id, subject_section_id, enrollment_status, enrollment_type, and period_id."
            ];
        }

        // Check if the student is already enrolled in this subject-section
        if ($this->studentEnrollmentModel->checkStudentEnrollment($data['user_id'], $data['subject_section_id'])) {
            return [
                "success" => false,
                "message" => "Student is already enrolled in this subject-section."
            ];
        }

        // Add student enrollment
        $MODEL_RESULT = $this->studentEnrollmentModel->addStudentEnrollment($data);

        if ($MODEL_RESULT['success']) {
            msgLog("CRUD", "[ADD] [STUDENT_ENROLLMENT] [USER_ID: " . $data["user_id"] . "] [SECTION_ID: " . $data["subject_section_id"] . "] | [" . $_SESSION["username"] . "] [" . $_SESSION["role"] . "]");
            return [
                "success" => true,
                "message" => "Student successfully enrolled in the subject-section."
            ];
        } else {
            return [
                "success" => false,
                "message" => "Error enrolling student. (" . $MODEL_RESULT['message'] . ")"
            ];
        }
    }

    // Get student enrollment details
    public function getEnrollment($enrollment_id)
    {
        $result = $this->studentEnrollmentModel->getEnrollmentDetails($enrollment_id);

        if ($result) {
            return [
                "success" => true,
                "data" => $result
            ];
        } else {
            return [
                "success" => false,
                "message" => "Enrollment not found."
            ];
        }
    }
}

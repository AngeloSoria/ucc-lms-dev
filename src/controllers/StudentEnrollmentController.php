<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Models']['StudentEnrollment']);
require_once(FILE_PATHS['Functions']['PHPLogger']);

class StudentEnrollmentController
{
    private $studentEnrollmentModel;

    public function __construct($db)
    {
        $this->studentEnrollmentModel = new StudentEnrollmentModel($db);
    }

    /**
     * Add multiple student enrollments.
     *
     * @param array $data - The data needed for enrollment.
     * @return array - Success or error message.
     */
    public function addStudentEnrollments($data)
    {
        // Validate input
        if (empty($data['user_ids']) || empty($data['subject_section_id'])) {
            return [
                "success" => false,
                "message" => "User IDs and Subject Section ID are required."
            ];
        }

        // Get subject_section details
        $subjectSection = $this->studentEnrollmentModel->getSubjectSectionById($data['subject_section_id']);
        if (!$subjectSection) {
            return [
                "success" => false,
                "message" => "Subject Section not found."
            ];
        }

        // Get active period
        $activePeriod = $this->studentEnrollmentModel->getActivePeriod();
        if (!$activePeriod) {
            return [
                "success" => false,
                "message" => "No active academic period found."
            ];
        }

        // Determine enrollment status
        $enrollmentStatus = $this->determineEnrollmentStatus($subjectSection['period_id'], $activePeriod['period_id']);

        // Initialize response
        $response = [
            "success" => true,
            "message" => "All students enrolled successfully.",
            "errors" => []
        ];

        // Process each user_id
        foreach ($data['user_ids'] as $userId) {
            // Prepare data for insertion
            $enrollmentData = [
                'user_id' => $userId,
                'subject_section_id' => $data['subject_section_id'],
                'period_id' => $subjectSection['period_id'],
                'enrollment_status' => $enrollmentStatus
            ];

            // Insert enrollment record
            $result = $this->studentEnrollmentModel->addStudentEnrollment($enrollmentData);

            if (!$result['success']) {
                // Log errors for failed enrollments
                $response['success'] = false;
                $response['errors'][] = [
                    'user_id' => $userId,
                    'message' => $result['message']
                ];
            } else {
                // Log successful enrollment
                $this->logOperation($userId, $data['subject_section_id']);
            }
        }

        // Adjust the response message if there were errors
        if (!$response['success']) {
            $response['message'] = "Some enrollments failed. See errors for details.";
        }

        return $response;
    }

    /**
     * Determine enrollment status based on period relationships.
     *
     * @param int $subjectPeriodId - The period ID of the subject section.
     * @param int $activePeriodId - The current active academic period ID.
     * @return string - The determined enrollment status.
     */
    private function determineEnrollmentStatus($subjectPeriodId, $activePeriodId)
    {
        if ($subjectPeriodId == $activePeriodId) {
            return 'active';
        } elseif ($subjectPeriodId > $activePeriodId) {
            return 'pending';
        } else {
            return 'completed';
        }
    }

    /**
     * Log operation details.
     *
     * @param int $userId
     * @param int $subjectSectionId
     */
    private function logOperation($userId, $subjectSectionId)
    {
        msgLog("CRUD", "[ADD] [STUDENT_ENROLLMENT] User: {$userId}, Subject Section: {$subjectSectionId}");
    }
}

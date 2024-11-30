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
        try {
            if (!isset($data['enroll_to_section_only'])) {
                // Validate input
                if (empty($data['user_ids']) || empty($data['subject_section_id'])) {
                    throw new Exception("User IDs or Subject Section ID are required.");
                }

                // Get subject_section details
                try {
                    $subjectSection = $this->studentEnrollmentModel->getSubjectSectionById($data['subject_section_id']);
                } catch (Exception $e) {
                    throw new Exception("Subject Section not found | " . $e->getMessage());
                }


                // Get active period
                $activePeriod = $this->studentEnrollmentModel->getActivePeriod();
                if (!$activePeriod) {
                    throw new Exception("No active academic period found.");
                }

                // Determine enrollment status
                $enrollmentStatus = $this->determineEnrollmentStatus($subjectSection['period_id'], $activePeriod['period_id']);

                // Process each user_id
                foreach ($data['user_ids'] as $userId) {
                    // Prepare data for insertion
                    $enrollmentData = [
                        'user_id' => $userId,
                        'subject_section_id' => $data['subject_section_id'],
                        'section_id' => $data['section_id'],
                        'enrollment_type' => $data['enrollment_type'],
                        'period_id' => $subjectSection['period_id'],
                        'enrollment_status' => $enrollmentStatus
                    ];

                    // Insert enrollment record
                    $this->studentEnrollmentModel->addStudentEnrollment($enrollmentData);
                }

                $this->enrollRegularStudentsToSubjects();

                return ['success' => true, "message" => "Student enrollment successful."];
            } else {
                // Process each user_id
                foreach ($data['user_ids'] as $userId) {
                    // Prepare data for insertion
                    $enrollmentData = [
                        'user_id' => $userId,
                        'section_id' => $data['section_id'],
                        'enrollment_type' => $data['enrollment_type'],
                        'enroll_to_section_only' => 1
                    ];

                    // Insert enrollment record
                    $this->studentEnrollmentModel->addStudentEnrollment($enrollmentData);
                }
                $this->enrollRegularStudentsToSubjects();
                return ['success' => true, "message" => "Student/s has been enrolled to the section."];
            }
        } catch (Exception $e) {
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    public function enrollRegularStudentsToSubjects()
    {
        try {
            if ($this->studentEnrollmentModel->enrollRegularStudentsToSubjects()) {
                return ['success' => true, "message" => "Automatically enrolled regular students to subjects of a section."];
            }
            msgLog("ASD", "asdasdasdasdasd");
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
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

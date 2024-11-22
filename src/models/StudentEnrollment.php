<?php
class StudentEnrollmentModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Fetch details of a subject section by ID.
     *
     * @param int $subjectSectionId
     * @return array|null
     */
    public function getSubjectSectionById($subjectSectionId)
    {
        $query = "SELECT period_id FROM subject_sections WHERE id = :subject_section_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':subject_section_id', $subjectSectionId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch the active academic period.
     *
     * @return array|null
     */
    public function getActivePeriod()
    {
        $query = "SELECT period_id FROM academic_period WHERE is_active = 1 LIMIT 1";
        $stmt = $this->db->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Add a new student enrollment record.
     *
     * @param array $data
     * @return array
     */
    public function addStudentEnrollment($data)
    {
        $query = "
            INSERT INTO student_enrollment (user_id, subject_section_id, enrollment_status, period_id)
            VALUES (:user_id, :subject_section_id, :enrollment_status, :period_id)
        ";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':subject_section_id', $data['subject_section_id'], PDO::PARAM_INT);
        $stmt->bindParam(':enrollment_status', $data['enrollment_status'], PDO::PARAM_STR);
        $stmt->bindParam(':period_id', $data['period_id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return ["success" => true];
        }

        return ["success" => false, "message" => $stmt->errorInfo()[2]];
    }

    /**
     * Check if a student is already enrolled in a subject section.
     *
     * @param int $userId
     * @param int $subjectSectionId
     * @return bool
     */
    public function isStudentAlreadyEnrolled($userId, $subjectSectionId)
    {
        $query = "
            SELECT COUNT(*) AS count 
            FROM student_enrollment 
            WHERE user_id = :user_id 
              AND subject_section_id = :subject_section_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':subject_section_id', $subjectSectionId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
}

<?php
class StudentEnrollmentModel
{
    private $db;
    private $table_name = "student_subject_section";

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
        try {
            $query = "SELECT period_id FROM subject_section WHERE subject_section_id = :subject_section_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':subject_section_id', $subjectSectionId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
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
        try {
            if (!isset($data['enroll_to_section_only'])) {
                // is Student enrolled
                if ($this->isStudentAlreadyEnrolled($data)) {
                    throw new Exception($data['user_id'] . " is already enrolled.");
                }

                // student_subject_section enrollment
                $query1 = "
                        INSERT INTO $this->table_name (user_id, subject_section_id, enrollment_status, period_id)
                        VALUES (:user_id, :subject_section_id, :enrollment_status, :period_id)";
                $stmt = $this->db->prepare($query1);

                $stmt->bindParam(':user_id', $data['user_id']);
                $stmt->bindParam(':subject_section_id', $data['subject_section_id'], PDO::PARAM_INT);
                $stmt->bindParam(':enrollment_status', $data['enrollment_status'], PDO::PARAM_STR);
                $stmt->bindParam(':period_id', $data['period_id'], PDO::PARAM_INT);

                $stmt->execute();

                // $this->enrollRegularStudentsToSubjects();

                return ["success" => true];
            } else {
                // is Student enrolled
                if ($this->isStudentAlreadyEnrolledFromSection($data)) {
                    throw new Exception($data['user_id'] . " is already enrolled.");
                }
                // student_section enrollment
                $query2 = "
                        INSERT INTO student_section (student_id, section_id, enrollment_type)
                        VALUES(:student_id, :section_id, :enrollment_type)";

                $stmt2 = $this->db->prepare($query2);
                $stmt2->bindParam(':student_id', $data['user_id']);
                $stmt2->bindParam(':section_id', $data['section_id']);
                $stmt2->bindParam(':enrollment_type', $data['enrollment_type']);

                $stmt2->execute();

                // $this->enrollRegularStudentsToSubjects();

                return ["success" => true];
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Check if a student is already enrolled in a subject section.
     *
     * @param int $userId
     * @param int $subjectSectionId
     * @return bool
     */
    public function isStudentAlreadyEnrolled($data)
    {
        $query1 = "
            SELECT COUNT(*) AS count 
            FROM $this->table_name 
            WHERE user_id = :user_id 
              AND subject_section_id = :subject_section_id
        ";
        $stmt = $this->db->prepare($query1);
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':subject_section_id', $data['subject_section_id'], PDO::PARAM_INT);
        $stmt->execute();

        $result_1 = $stmt->fetch(PDO::FETCH_ASSOC);

        // $query2 = "
        //     SELECT COUNT(*) AS count
        //     FROM student_section
        //     WHERE student_id = :student_id AND section_id = :section_id
        // ";
        // $stmt = $this->db->prepare($query2);
        // $stmt->bindParam(':student_id', $data['user_id'], PDO::PARAM_INT);
        // $stmt->bindParam(':section_id', $data['section_id'], PDO::PARAM_INT);
        // $stmt->execute();

        // $result_2 = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result_1['count'] > 0);
    }
    public function isStudentAlreadyEnrolledFromSection($data)
    {
        $query = "
            SELECT COUNT(*) AS count
            FROM student_section
            WHERE student_id = :student_id AND section_id = :section_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':section_id', $data['section_id'], PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result['count'] > 0);
    }

    public function enrollRegularStudentsToSubjects()
    {
        try {
            $query = "
                    INSERT INTO student_subject_section (user_id, subject_section_id, enrollment_status, period_id)
                    SELECT
                        ss.student_id AS user_id,
                        subsec.subject_section_id, 
                        'active' AS enrollment_status,
                        subsec.period_id
                    FROM
                        student_section ss
                    JOIN
                        subject_section subsec 
                        ON ss.section_id = subsec.section_id
                    WHERE
                        ss.enrollment_type = 'regular'
                        AND NOT EXISTS (
                            SELECT 1 
                            FROM student_subject_section sss 
                            WHERE sss.user_id = ss.student_id
                            AND sss.subject_section_id = subsec.subject_section_id)";


            $stmt = $this->db->prepare($query);
            $stmt->execute();
            msgLog("FOO", "BAR");
            return ['success' => true];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}

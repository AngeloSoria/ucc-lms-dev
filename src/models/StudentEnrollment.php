<? class StudentEnrollmentModel
{
    private $conn;
    private $table_name = "student_enrollments"; // Replace with your actual table name

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Add student enrollment
    public function addStudentEnrollment($data)
    {
        try {
            $this->conn->beginTransaction();

            // Query to insert the enrollment details
            $query = "INSERT INTO {$this->table_name} 
                      (user_id, subject_section_id, enrollment_status, enrollment_type, period_id) 
                      VALUES 
                      (:user_id, :subject_section_id, :enrollment_status, :enrollment_type, :period_id)";
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':user_id', $data['user_id']);
            $stmt->bindParam(':subject_section_id', $data['subject_section_id']);
            $stmt->bindParam(':enrollment_status', $data['enrollment_status']);
            $stmt->bindParam(':enrollment_type', $data['enrollment_type']);
            $stmt->bindParam(':period_id', $data['period_id']);

            $stmt->execute();
            $this->conn->commit();

            return ["success" => true];
        } catch (PDOException $e) {
            $this->conn->rollBack(); // Rollback transaction on error
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Check if student is already enrolled in a subject-section
    public function checkStudentEnrollment($user_id, $subject_section_id)
    {
        $query = "SELECT COUNT(*) FROM {$this->table_name} 
                  WHERE user_id = :user_id AND subject_section_id = :subject_section_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':subject_section_id', $subject_section_id);
        $stmt->execute();

        return $stmt->fetchColumn() > 0; // Returns true if already enrolled
    }

    // Get student enrollment details by enrollment ID
    public function getEnrollmentDetails($enrollment_id)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE enrollment_id = :enrollment_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':enrollment_id', $enrollment_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

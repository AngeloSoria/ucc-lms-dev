<?php
class StudentSectionModel
{
    private $conn;
    private $table_name = "student_section"; // Replace with your actual table name

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Add student to section
    public function addStudentToSection($data)
    {
        try {
            $this->conn->beginTransaction();

            // Insert query
            $query = "INSERT INTO {$this->table_name} (student_id, section_id, enrollment_type) 
                      VALUES (:student_id, :section_id, :enrollment_type)";
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':student_id', $data['student_id']);
            $stmt->bindParam(':section_id', $data['section_id']);
            $stmt->bindParam(':enrollment_type', $data['enrollment_type']);

            $stmt->execute();
            $this->conn->commit();

            return ["success" => true];
        } catch (PDOException $e) {
            $this->conn->rollBack(); // Rollback transaction on error
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Check if student is already in the section
    public function checkStudentInSection($student_id, $section_id)
    {
        $query = "SELECT COUNT(*) FROM {$this->table_name} WHERE student_id = :student_id AND section_id = :section_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':section_id', $section_id);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }
}

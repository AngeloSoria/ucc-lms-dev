<?php
class SubjectSectionModel
{
    private $conn;
    private $table_name = "subject_section"; // Table name

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Add subject section
    public function addSubjectSection($data)
    {
        try {
            $this->conn->beginTransaction();

            // Query to insert subject section
            $query = "INSERT INTO {$this->table_name} 
                      (subject_id, section_id, subject_section_image, teacher_id, period_id) 
                      VALUES 
                      (:subject_id, :section_id, :subject_section_image, :teacher_id, :period_id)";
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':subject_id', $data['subject_id']);
            $stmt->bindParam(':section_id', $data['section_id']);
            $stmt->bindParam(':subject_section_image', $data['subject_section_image']);
            $stmt->bindParam(':teacher_id', $data['teacher_id']);
            $stmt->bindParam(':period_id', $data['period_id']);

            $stmt->execute();
            $this->conn->commit();

            return ["success" => true, "message" => "Subject Section added successfully"];
        } catch (PDOException $e) {
            $this->conn->rollBack(); // Rollback transaction if an error occurs
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Check if subject section exists
    public function checkSubjectSectionExists($subject_id, $section_id, $period_id)
    {
        $query = "SELECT COUNT(*) FROM {$this->table_name} 
                  WHERE subject_id = :subject_id AND section_id = :section_id AND period_id = :period_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':subject_id', $subject_id);
        $stmt->bindParam(':section_id', $section_id);
        $stmt->bindParam(':period_id', $period_id);
        $stmt->execute();

        return $stmt->fetchColumn() > 0; // Returns true if subject section exists
    }

    // Get subject section details by subject_section_id
    public function getSubjectSectionDetails($subject_section_id)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE subject_section_id = :subject_section_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':subject_section_id', $subject_section_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch and return subject section details
    }
}

<?php
class SubjectSectionModel
{
    private $conn;
    private $table_name = "subject_section"; // Table name

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Check if the subject is already assigned to the section and period
    private function isSubjectAssignedToSection($subject_id, $section_id, $period_id)
    {
        $query = "SELECT COUNT(*) FROM {$this->table_name} 
                   WHERE subject_id = :subject_id AND section_id = :section_id AND period_id = :period_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':subject_id', $subject_id);
        $stmt->bindParam(':section_id', $section_id);
        $stmt->bindParam(':period_id', $period_id);
        $stmt->execute();

        return $stmt->fetchColumn() > 0; // Returns true if a record exists
    }

    // Add subject section
    public function addSubjectSection($data)
    {
        try {
            $this->conn->beginTransaction();

            // Determine the period_id based on the semester
            $period_id = $this->getPeriodId($data['section_id']);
            if ($period_id === null) {
                $period_id = null; // Allow null if no active period is found
            }

            // Log the data being passed for debugging
            msgLog("DEBUG", "Adding Subject Section with data: " . json_encode($data));

            // Check if the subject is already assigned to the section and period
            if ($this->isSubjectAssignedToSection($data['subject_id'], $data['section_id'], $period_id)) {
                return ["success" => false, "message" => "Subject is already assigned to this section and period."];
            }

            // Fetch the semester for the subject from the subject table
            $subjectQuery = "SELECT semester FROM subjects WHERE subject_id = :subject_id LIMIT 1";
            $stmtSubject = $this->conn->prepare($subjectQuery);
            $stmtSubject->bindParam(':subject_id', $data['subject_id']);
            $stmtSubject->execute();
            $subjectData = $stmtSubject->fetch(PDO::FETCH_ASSOC);

            if (!$subjectData) {
                return ["success" => false, "message" => "Subject not found."];
            }

            $subjectSemester = $subjectData['semester'];

            // Fetch the semester for the section from the section table
            $sectionQuery = "SELECT semester FROM section WHERE section_id = :section_id LIMIT 1";
            $stmtSection = $this->conn->prepare($sectionQuery);
            $stmtSection->bindParam(':section_id', $data['section_id']);
            $stmtSection->execute();
            $sectionData = $stmtSection->fetch(PDO::FETCH_ASSOC);

            if (!$sectionData) {
                return ["success" => false, "message" => "Section not found."];
            }

            $sectionSemester = $sectionData['semester'];

            // Check if the subject's semester matches the section's semester
            if ($subjectSemester != $sectionSemester) {
                return ["success" => false, "message" => "Subject's semester does not match the section's semester."];
            }

            // Query to insert subject section
            $query = "INSERT INTO {$this->table_name} 
                       (section_id, subject_id, subject_section_image, teacher_id, period_id) 
                       VALUES 
                       (:section_id, :subject_id, :subject_section_image, :teacher_id, :period_id)";
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':section_id', $data['section_id']);
            $stmt->bindParam(':subject_id', $data['subject_id']);
            $stmt->bindParam(':subject_section_image', $data['subject_section_image']);
            $stmt->bindParam(':teacher_id', $data['teacher_id']);
            $stmt->bindParam(':period_id', $period_id);

            // Execute the query
            msgLog("DEBUG", "Starting transaction for subject section insert.");
            $stmt->execute(); // Execute the insert query
            msgLog("DEBUG", "Subject Section inserted: " . json_encode($data));

            $this->conn->commit();

            return ["success" => true, "message" => "Subject Section added successfully"];
        } catch (PDOException $e) {
            $this->conn->rollBack(); // Rollback transaction if an error occurs
            return ["success" => false, "message" => "Error: " . $e->getMessage() . " SQLSTATE: " . $e->getCode()];
        }
    }

    // Check if subject section exists
    public function checkSubjectSectionExists($subject_id, $section_id, $period_id)
    {
        $query = "SELECT COUNT(*) FROM {$this->table_name} 
                  WHERE subject_id = :subject_id AND section_id = :section_id AND period_id = :period_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn(); // Returns the active period_id or null if none found
    }

    // Method to get the active semester dynamically
    private function getActiveSemester()
    {
        $queryActive = "SELECT semester FROM academic_period WHERE is_active = 1 LIMIT 1";
        $stmtActive = $this->conn->prepare($queryActive);
        $stmtActive->execute();
        $activePeriod = $stmtActive->fetch(PDO::FETCH_ASSOC);

        return $activePeriod ? $activePeriod['semester'] : null;
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

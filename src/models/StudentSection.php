<?php

class StudentSectionModel
{
    private $conn;
    private $table_name = "student_section";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function isSectionExist($section_id)
    {
        $query = "SELECT COUNT(*) FROM section WHERE section_id = :section_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':section_id', $section_id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function isStudentRole($student_id)
    {
        $query = "SELECT role FROM users WHERE user_id = :student_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['role'] == 'Student';
    }

    public function checkStudentInSection($student_id, $section_id)
    {
        $query = "SELECT COUNT(*) FROM student_section WHERE student_id = :student_id AND section_id = :section_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':section_id', $section_id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function addStudentToSection($data)
    {
        $query = "INSERT INTO student_section (student_id, section_id, enrollment_type) VALUES (:student_id, :section_id, :enrollment_type)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $data['student_id']);
        $stmt->bindParam(':section_id', $data['section_id']);
        $stmt->bindParam(':enrollment_type', $data['enrollment_type']);
        if ($stmt->execute()) {
            return ["success" => true];
        }
        return ["success" => false, "message" => "Error adding student to section."];
    }

    public function searchStudents($query)
    {
        $searchQuery = "%{$query}%";
        $query = "SELECT user_id, CONCAT(first_name, ' ', last_name) AS name FROM users WHERE role = 'Student' AND (first_name LIKE :query OR last_name LIKE :query)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':query', $searchQuery);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchSections($query)
    {
        // Fetch the active period_id from academic_period where is_active = 1
        $activePeriodQuery = "SELECT period_id FROM academic_period WHERE is_active = 1 LIMIT 1";
        $activeStmt = $this->conn->prepare($activePeriodQuery);
        $activeStmt->execute();
        $activePeriod = $activeStmt->fetch(PDO::FETCH_ASSOC);

        if (!$activePeriod) {
            // If no active period is found, return an empty array
            return [];
        }

        $activePeriodId = $activePeriod['period_id'];

        // Fetch sections linked to the active period and matching the query
        $searchQuery = "%{$query}%";
        $query = "
            SELECT section_id, section_name AS name
            FROM section
            WHERE section_name LIKE :query AND period_id = :active_period_id
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':query', $searchQuery);
        $stmt->bindParam(':active_period_id', $activePeriodId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalEnrolleesInSection($section_id)
    {
        try {
            // Prepare the SQL query to count enrollees in a specific section
            $query = "SELECT COUNT(*) AS total_students FROM student_section WHERE section_id = :section_id";

            // Assuming $this->db is a PDO instance
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':section_id', $section_id, PDO::PARAM_INT);
            $stmt->execute();

            // Fetch the total count
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Return the total number of students
            return $result['total_students'];
        } catch (Exception $e) {
            // Throw an exception if something goes wrong
            throw new Exception($e->getMessage());
        }
    }


    public function getAllEnrolledStudentIdBySectionId($section_id)
    {
        try {
            $query = "SELECT student_id FROM  $this->table_name WHERE section_id = :section_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':section_id', $section_id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($result)) {
                return $result;
            } else {
                throw new Exception("No students enrolled in section ($section_id)");
            }
        } catch (Exception  $e) {
            throw new Exception("[MODEL]" . $e->getMessage());
        }
    }
}

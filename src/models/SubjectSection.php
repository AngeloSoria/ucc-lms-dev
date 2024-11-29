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
                return ["success" => false, "message" => "Subject not found. "];
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
            $query = "INSERT INTO $this->table_name
                       (section_id, subject_id, teacher_id, period_id) 
                       VALUES 
                       (:section_id, :subject_id, :teacher_id, :period_id)";
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':section_id', $data['section_id']);
            $stmt->bindParam(':subject_id', $data['subject_id']);
            // $stmt->bindParam(':subject_section_image', $data['subject_section_image']);
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

    // Get the period_id based on the section's semester
    private function getPeriodId($section_id)
    {
        // First, get the semester for the given section_id
        $querySemester = "SELECT semester FROM section WHERE section_id = :section_id LIMIT 1";
        $stmtSemester = $this->conn->prepare($querySemester);
        $stmtSemester->bindParam(':section_id', $section_id);
        $stmtSemester->execute();
        $sectionData = $stmtSemester->fetch(PDO::FETCH_ASSOC);

        if (!$sectionData) {
            return null; // Return null if no section found
        }

        $semester = $sectionData['semester'];

        // Now, get the active academic period and corresponding period_id based on the semester
        $queryActive = "SELECT period_id, academic_year_start, semester 
                     FROM academic_period 
                     WHERE is_active = 1 
                     LIMIT 1";

        $stmtActive = $this->conn->prepare($queryActive);
        $stmtActive->execute();
        $activePeriod = $stmtActive->fetch(PDO::FETCH_ASSOC);

        if (!$activePeriod) {
            return null; // Return null if no active period found
        }

        $activeYearStart = $activePeriod['academic_year_start'];
        $activeSemester = $activePeriod['semester'];

        // Determine the period_id based on the section's semester
        if ($activeSemester == $semester) {
            return $activePeriod['period_id'];
        }

        // If the active semester is 1 (first semester) and the section's semester is 2 (second semester)
        if ($activeSemester == 1 && $semester == 2) {
            $querySecondSem = "SELECT period_id 
                           FROM academic_period 
                           WHERE academic_year_start = :activeYearStart AND semester = 2 
                           LIMIT 1";
            $stmtSecondSem = $this->conn->prepare($querySecondSem);
            $stmtSecondSem->bindParam(':activeYearStart', $activeYearStart);
            $stmtSecondSem->execute();
            $secondSemPeriod = $stmtSecondSem->fetch(PDO::FETCH_ASSOC);

            return $secondSemPeriod['period_id'] ?? null;
        }

        // If the active semester is 2 (second semester) and the section's semester is 1 (first semester) of the next year
        if ($activeSemester == 2 && $semester == 1) {
            $queryNextYearFirstSem = "SELECT period_id 
                                   FROM academic_period 
                                   WHERE academic_year_start = :nextYearStart AND semester = 1 
                                   LIMIT 1";
            $nextYearStart = $activeYearStart + 1;
            $stmtNextYearFirstSem = $this->conn->prepare($queryNextYearFirstSem);
            $stmtNextYearFirstSem->bindParam(':nextYearStart', $nextYearStart);
            $stmtNextYearFirstSem->execute();
            $nextYearFirstSemPeriod = $stmtNextYearFirstSem->fetch(PDO::FETCH_ASSOC);

            return $nextYearFirstSemPeriod['period_id'] ?? null;
        }

        return null; // If no matching period is found, return null
    }

    // Fetch the active period_id (helper method)
    public function getActivePeriodId()
    {
        $query = "SELECT period_id FROM academic_period WHERE is_active = 1 LIMIT 1";
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

    // Delete subject section by ID
    public function deleteSubjectSection($subject_section_id)
    {
        try {
            $query = "DELETE FROM {$this->table_name} WHERE subject_section_id = :subject_section_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':subject_section_id', $subject_section_id);
            $stmt->execute();

            return ["success" => true, "message" => "Subject Section deleted successfully"];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function searchTeacher($query, $educationalLevel)
    {
        $searchQuery = "%{$query}%";
        $query = "
        SELECT 
            u.user_id, 
            CONCAT(u.first_name, ' ', u.last_name) AS name, 
            t.educational_level
        FROM 
            users u
        INNER JOIN 
            educational_level t ON u.user_id = t.user_id
        WHERE 
            u.role = 'Teacher'
            AND (u.first_name LIKE :query OR u.last_name LIKE :query)";

        // If an educational level filter is provided, include it in the query.
        if ($educationalLevel) {
            $query .= " AND t.educational_level = :educational_level";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':query', $searchQuery);

        if ($educationalLevel) {
            $stmt->bindParam(':educational_level', $educationalLevel);
        }



        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function searchStudent($query, $educationalLevel)
    {
        $searchQuery = "%{$query}%";
        $query = "
        SELECT
            u.user_id, 
            CONCAT(u.first_name, ' ', middle_name, ' ', u.last_name) AS name, 
            t.educational_level
        FROM
            users u
        INNER JOIN 
            educational_level t ON u.user_id = t.user_id
        WHERE 
            u.role = 'Student'
            AND (u.first_name LIKE :query OR u.last_name LIKE :query)";

        // If an educational level filter is provided, include it in the query.
        if ($educationalLevel) {
            $query .= " AND t.educational_level = :educational_level";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':query', $searchQuery);

        if ($educationalLevel) {
            $stmt->bindParam(':educational_level', $educationalLevel);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function searchSections($query)
    {
        try {
            // Fetch the active period_id and semester from the academic_period where is_active = 1
            $activePeriodQuery = "
                SELECT period_id, semester
                FROM academic_period
                WHERE is_active = 1
                LIMIT 1
            ";
            $stmt = $this->conn->prepare($activePeriodQuery);
            $stmt->execute();
            $activePeriod = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$activePeriod) {
                // Log an error if no active academic period is found
                error_log("No active academic period found.");
                return []; // Return an empty array if no active period is found
            }

            $activePeriodId = $activePeriod['period_id'];

            // Search sections based on the active period ID
            $sectionQuery = "
                SELECT 
                    section_id, 
                    section_name AS name
                FROM 
                    section
                WHERE 
                    section_name LIKE :query
                    AND period_id = :activePeriodId
            ";
            $stmt = $this->conn->prepare($sectionQuery);
            $stmt->bindValue(":query", "%{$query}%", PDO::PARAM_STR);  // Use parameterized query
            $stmt->bindValue(":activePeriodId", $activePeriodId, PDO::PARAM_INT);

            $stmt->execute();
            $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return sections or an empty array if none are found
            return $sections;
        } catch (Exception $e) {
            // Log the exception and return an empty array
            error_log("Error in searchSections: " . $e->getMessage());
            return [];
        }
    }
    public function searchSubject($query, $educationalLevel = null)
    {
        // Search for subjects that match the query term
        $searchQuery = "%{$query}%";  // Add wildcards to match any part of the subject name

        // Base query to search subjects by name
        $query = "
            SELECT 
                subject_id, 
                subject_name AS name,
                subject_code,
                semester
            FROM 
                subjects
            WHERE 
                (subject_name LIKE :query OR subject_code LIKE :query)";

        // If educational_level is provided, add the filter to the query
        if ($educationalLevel) {
            $query .= " AND educational_level = :educational_level";
        }

        // Prepare and execute the query
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':query', $searchQuery);

        // Bind educational_level if it's provided
        if ($educationalLevel) {
            $stmt->bindParam(':educational_level', $educationalLevel);
        }

        $stmt->execute();

        // Fetch all results
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return data in the format that select2 expects
        return $subjects;
    }

    public function getAllEnrolledSubjectsFromSectionBySectionId($section_id)
    {
        try {
            $query = "SELECT * FROM $this->table_name WHERE section_id = :section_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":section_id", $section_id);
            $stmt->execute();

            // Get All subject_section data.
            $retrievedSubjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['success' => true, 'data' => $retrievedSubjects];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getAllEnrolledSubjectsFromSectionByTeacherId($teacher_id)
    {
        try {
            $query = "SELECT * FROM $this->table_name WHERE teacher_id = :teacher_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":teacher_id", $teacher_id);
            $stmt->execute();

            // Get All subject_section data.
            $retrievedSubjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['success' => true, 'data' => $retrievedSubjects];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}

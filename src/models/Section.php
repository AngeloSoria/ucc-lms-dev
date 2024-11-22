<?php
class Section
{
    private $conn;
    private $table_name = "section";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Method to determine the appropriate period_id
    private function getPeriodId($semester)
    {
        // Query to get the active academic period
        $queryActive = "SELECT period_id, academic_year_start, semester 
                    FROM academic_period 
                    WHERE is_active = 1 
                    LIMIT 1";

        $stmtActive = $this->conn->prepare($queryActive);
        $stmtActive->execute();
        $activePeriod = $stmtActive->fetch(PDO::FETCH_ASSOC);

        if (!$activePeriod) {
            // No active academic period found
            return null; // Return null if no active period
        }

        // Extract details of the active academic period
        $activeYearStart = $activePeriod['academic_year_start'];
        $activeSemester = $activePeriod['semester'];

        // Case 1: Adding a section for the active semester
        if ($activeSemester == $semester) {
            // If the active semester matches, return the current period ID
            return $activePeriod['period_id'];
        }

        // Case 2: Adding a section for the second semester of the current academic year
        if ($activeSemester == 1 && $semester == 2) {
            // Query to get the second semester period for the same academic year
            $querySecondSem = "SELECT period_id FROM academic_period 
                           WHERE academic_year_start = :activeYearStart AND semester = 2 
                           LIMIT 1";

            $stmtSecondSem = $this->conn->prepare($querySecondSem);
            $stmtSecondSem->bindParam(':activeYearStart', $activeYearStart);
            $stmtSecondSem->execute();
            $secondSemPeriod = $stmtSecondSem->fetch(PDO::FETCH_ASSOC);

            return $secondSemPeriod['period_id'] ?? null; // Return the second semester period ID
        }

        // Case 3: Adding a section for the first semester of the next academic year
        if ($activeSemester == 2 && $semester == 1) {
            // Query to check if next academic year's first semester exists
            $queryNextYearFirstSem = "SELECT period_id FROM academic_period 
                                  WHERE academic_year_start = :nextYearStart AND semester = 1 
                                  LIMIT 1";

            $nextYearStart = $activeYearStart + 1; // Calculate the next academic year start
            $stmtNextYearFirstSem = $this->conn->prepare($queryNextYearFirstSem);
            $stmtNextYearFirstSem->bindParam(':nextYearStart', $nextYearStart);
            $stmtNextYearFirstSem->execute();
            $nextYearFirstSemPeriod = $stmtNextYearFirstSem->fetch(PDO::FETCH_ASSOC);

            return $nextYearFirstSemPeriod['period_id'] ?? null; // Return the first semester period ID of the next year
        }

        // No matching period_id found
        return null; // Return null if no period found
    }

    // Method to automatically update sections with null period_id
    public function updateAcademicPeriod()
    {
        // Get the active semester from the query
        $semester = $this->getActiveSemester();

        // Get the new period_id for the current or next semester
        $newPeriodId = $this->getPeriodId($semester);

        if ($newPeriodId === null) {
            // Handle case when no period_id is found
            return false;
        }

        // Update all sections with NULL period_id to the new period_id
        $this->updateSectionPeriod($newPeriodId);

        return true; // Successfully updated all sections
    }

    // Method to get the active semester dynamically
    private function getActiveSemester()
    {
        // Query to get the active academic period's semester
        $queryActive = "SELECT semester FROM academic_period WHERE is_active = 1 LIMIT 1";

        $stmtActive = $this->conn->prepare($queryActive);
        $stmtActive->execute();
        $activePeriod = $stmtActive->fetch(PDO::FETCH_ASSOC);

        // Return the semester of the active period
        return $activePeriod ? $activePeriod['semester'] : null;
    }


    // Method to automatically update sections with null period_id
    private function updateSectionPeriod($newPeriodId)
    {
        // Update sections with NULL or outdated period_id
        $updateQuery = "UPDATE " . $this->table_name . " 
                        SET period_id = :newPeriodId 
                        WHERE period_id IS NULL";

        $stmtUpdate = $this->conn->prepare($updateQuery);
        $stmtUpdate->bindParam(':newPeriodId', $newPeriodId);

        return $stmtUpdate->execute(); // Return true if successful, false otherwise
    }

    public function addSection($data)
    {
        // Determine the period_id based on the semester
        $period_id = $this->getPeriodId($data['semester']);

        // If period_id is still null, store null in the section
        if ($period_id === null) {
            $period_id = null;
        }

        // Adjust the column order in the query to match your table structure
        $query = "INSERT INTO " . $this->table_name . " (section_name, program_id, year_level, semester, section_image, adviser_id, period_id) 
                  VALUES (:section_name, :program_id, :year_level, :semester, :adviser_id, :period_id)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters in the order of the columns
        $stmt->bindParam(':section_name', $data['section_name']);
        $stmt->bindParam(':program_id', $data['program_id']);
        $stmt->bindParam(':year_level', $data['year_level']);
        $stmt->bindParam(':semester', $data['semester']);
        $stmt->bindParam(':adviser_id', $data['adviser_id']);
        $stmt->bindParam(':period_id', $period_id); // Bind the dynamically determined period_id

        return $stmt->execute(); // Return true if successful, false otherwise
    }

    public function getAllSections()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as associative array
    }

    public function sectionExists($data)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " 
                  WHERE section_name = :section_name AND program_id = :program_id 
                  AND year_level = :year_level AND semester = :semester";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':section_name', $data['section_name']);
        $stmt->bindParam(':program_id', $data['program_id']);
        $stmt->bindParam(':year_level', $data['year_level']);
        $stmt->bindParam(':semester', $data['semester']);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['count'] > 0; // Return true if section exists, false otherwise
    }

    public function getSectionById($section_id)
    {
        try {
            $query = "SELECT * FROM $this->table_name WHERE section_id = :section_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':section_id', $section_id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}

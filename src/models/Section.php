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
            return null; // No active period found
        }

        $activeYearStart = $activePeriod['academic_year_start'];
        $activeSemester = $activePeriod['semester'];

        // Case 1: If adding a section for the active semester (same semester as active period)
        if ($activeSemester == $semester) {
            return $activePeriod['period_id'];
        }

        // Case 2: If adding a section for the first semester of the next academic year
        if ($activeSemester == 2 && $semester == 1) {
            $nextYearStart = $activeYearStart + 1;

            // Query to find period_id for 1st semester of the next academic year
            $queryNextYearFirstSem = "SELECT period_id 
                                  FROM academic_period 
                                  WHERE academic_year_start = :nextYearStart AND semester = 1 
                                  LIMIT 1";
            $stmtNextYearFirstSem = $this->conn->prepare($queryNextYearFirstSem);
            $stmtNextYearFirstSem->bindParam(':nextYearStart', $nextYearStart);
            $stmtNextYearFirstSem->execute();
            $nextYearFirstSemPeriod = $stmtNextYearFirstSem->fetch(PDO::FETCH_ASSOC);

            // If no period_id is found, return null
            return $nextYearFirstSemPeriod['period_id'] ?? null;
        }

        // Case 3: If adding a section for the second semester of the current academic year
        if ($activeSemester == 1 && $semester == 2) {
            // Query to find period_id for 2nd semester of the current academic year
            $querySecondSem = "SELECT period_id 
                           FROM academic_period 
                           WHERE academic_year_start = :activeYearStart AND semester = 2 
                           LIMIT 1";
            $stmtSecondSem = $this->conn->prepare($querySecondSem);
            $stmtSecondSem->bindParam(':activeYearStart', $activeYearStart);
            $stmtSecondSem->execute();
            $secondSemPeriod = $stmtSecondSem->fetch(PDO::FETCH_ASSOC);

            // If no period_id is found, return null
            return $secondSemPeriod['period_id'] ?? null;
        }

        return null; // Return null if no matching period_id found
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
        try {
            // Determine the period_id based on the semester
            $period_id = $this->getPeriodId($data['semester']);

            // If period_id is still null, store null in the section
            if ($period_id === null) {
                $period_id = null;
            }


            // Adjust the column order in the query to match your table structure
            $query = "INSERT INTO " . $this->table_name . " (section_name, program_id, year_level, semester, adviser_id, period_id) 
                      VALUES (:section_name, :program_id, :year_level, :semester, :adviser_id, :period_id)";

            $stmt = $this->conn->prepare($query);

            // Bind parameters in the order of the columns
            $stmt->bindParam(':section_name', $data['section_name']);
            $stmt->bindParam(':program_id', $data['program_id']);
            $stmt->bindParam(':year_level', $data['year_level']);
            $stmt->bindParam(':semester', $data['semester']);
            $stmt->bindParam(':adviser_id', $data['adviser_id']);
            $stmt->bindParam(':period_id', $period_id); // Bind the dynamically determined period_id

            // Execute the statement and return success message
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Section added successfully.'];
            } else {
                return ['success' => false, 'message' => 'Failed to add section.'];
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage()); // Return error message if any exception occurs
        }
    }

    public function getAllSections()
    {
        // Query to get the active period ID (with LIMIT 1)
        $queryActive = "SELECT period_id, academic_year_start, semester 
        FROM academic_period 
        WHERE is_active = 1 
        LIMIT 1";

        $stmtActive = $this->conn->prepare($queryActive);
        $stmtActive->execute();
        $activePeriod = $stmtActive->fetch(PDO::FETCH_ASSOC);

        // Check if an active period was found
        if (!$activePeriod) {
            return []; // Return an empty array if no active period is found
        }

        // Use the retrieved period_id in the main query
        $query = "SELECT * FROM " . $this->table_name . " WHERE period_id = :period_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':period_id', $activePeriod['period_id']);
        $stmt->execute();

        // Fetch and return all results as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function sectionExistsById($section_id)
    {
        // $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " 
        //           WHERE section_name = :section_name AND program_id = :program_id 
        //           AND year_level = :year_level AND semester = :semester";

        $query = "SELECT COUNT(*) as count FROM $this->table_name WHERE section_id = :section_id";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':section_id', $section_id);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['count'] > 0; // Return true if section exists, false otherwise
    }

    public function getSectionById($section_id)
    {
        try {
            $query = "SELECT * FROM $this->table_name WHERE section_id = :section_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':section_id', $section_id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function updateSectionById($section_id, $data)
    {
        try {
            if (!$this->sectionExistsById($section_id)) {
                throw new Exception("No section found with id ($section_id).");
            }

            $query = "UPDATE $this->table_name SET section_name = :section_name, program_id = :program_id, year_level = :year_level, adviser_id = :adviser_id WHERE section_id = :section_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':section_id', $data['section_id']);
            $stmt->bindParam(':section_name', $data['section_name']);
            $stmt->bindParam(':program_id', $data['program_id']);
            $stmt->bindParam(':year_level', $data['year_level']);
            $stmt->bindParam(':adviser_id', $data['adviser_id']);

            $stmt->execute();
            return ['success' => true];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}

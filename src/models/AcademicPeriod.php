<?php
class AcademicPeriod
{
    private $conn;
    private $table_name = 'academic_terms';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Check if the term is active based on the current date
    public function checkActiveTerm()
    {
        date_default_timezone_set('Asia/Manila'); // Set to Manila time zone
        $currentDate = date('Y-m-d');

        // Get all terms to check the current one and update accordingly
        $query = "SELECT term_id, start_date, end_date, is_active FROM {$this->table_name}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Fetch all terms
        $terms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Loop through all terms and update the active status
        foreach ($terms as $term) {
            $termId = $term['term_id'];
            $startDate = $term['start_date'];
            $endDate = $term['end_date'];
            $isActive = $term['is_active'];

            // If the current date is within the start and end date
            if ($currentDate >= $startDate && $currentDate <= $endDate) {
                // If it's within the range and inactive, set it to active
                if ($isActive == 0) {
                    $this->toggleTermActiveStatus($termId, 1);  // Set as active
                }
            } else {
                // If it's not within the range, set it to inactive
                if ($isActive == 1) {
                    $this->toggleTermActiveStatus($termId, 0);  // Set as inactive
                }
            }
        }
    }


    // Toggle the active status of a term
    private function toggleTermActiveStatus($termId, $status)
    {
        $query = "UPDATE {$this->table_name} SET is_active = :status WHERE term_id = :term_id";
        $stmt = $this->conn->prepare($query);

        // Bind the parameters
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':term_id', $termId, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();
    }

    // Check if an active academic year exists
    public function checkAcademicYear($academicYear)
    {
        $query = "SELECT * FROM academic_terms WHERE academic_year = :academicYear AND is_active = 1";  // Check if active academic year exists
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':academicYear', $academicYear, PDO::PARAM_STR);
        $stmt->execute();

        // Return true if an active academic year exists
        return $stmt->rowCount() > 0;
    }

    // Add academic year with two semesters
    public function addAcademicYearWithSemesters($academicYear, $firstSemesterDates, $secondSemesterDates)
    {
        $this->conn->beginTransaction();  // Start a transaction for consistency

        try {
            // Insert first semester
            $query = "INSERT INTO academic_terms (academic_year, semester, start_date, end_date, is_active) 
                      VALUES (:academicYear, '1st Semester', :firstStartDate, :firstEndDate, 1)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':academicYear', $academicYear, PDO::PARAM_STR);
            $stmt->bindParam(':firstStartDate', $firstSemesterDates['start_date'], PDO::PARAM_STR);
            $stmt->bindParam(':firstEndDate', $firstSemesterDates['end_date'], PDO::PARAM_STR);
            $stmt->execute();

            // Insert second semester
            $query = "INSERT INTO academic_terms (academic_year, semester, start_date, end_date, is_active) 
                      VALUES (:academicYear, '2nd Semester', :secondStartDate, :secondEndDate, 1)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':academicYear', $academicYear, PDO::PARAM_STR);
            $stmt->bindParam(':secondStartDate', $secondSemesterDates['start_date'], PDO::PARAM_STR);
            $stmt->bindParam(':secondEndDate', $secondSemesterDates['end_date'], PDO::PARAM_STR);
            $stmt->execute();

            $this->conn->commit();  // Commit the transaction
            return ['status' => 'success', 'message' => 'Academic year with two semesters added successfully.'];
        } catch (Exception $e) {
            $this->conn->rollBack();  // Rollback the transaction in case of error
            return ['status' => 'error', 'message' => 'Failed to add academic year.'];
        }
    }

    // Get all academic terms
    public function getAllTerms()
    {
        $query = "SELECT term_id, academic_year, semester, start_date, end_date, is_active FROM academic_terms ORDER BY start_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Fetch and return the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all active academic terms
    public function getActiveTerms()
    {
        $query = "SELECT term_id, academic_year, semester, start_date, end_date, is_active FROM academic_terms WHERE is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Fetch and return the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

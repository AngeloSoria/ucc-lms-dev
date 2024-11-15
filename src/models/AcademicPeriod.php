<?php
class AcademicPeriod
{
    private $conn;
    private $table_name = 'academic_period';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Check if the term is active based on the current date
    public function checkActiveTerm()
    {
        date_default_timezone_set('Asia/Manila'); // Set to Manila time zone
        $currentDate = date('Y-m-d');

        try {
            // Begin a transaction to ensure all updates occur as a unit
            $this->conn->beginTransaction();

            // Get all terms to check the current one and update accordingly
            $query = "SELECT period_id, start_date, end_date, is_active FROM {$this->table_name}";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            // Fetch all terms
            $terms = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Loop through all terms and update the active status
            foreach ($terms as $term) {
                $termId = $term['period_id'];
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

            // Commit the transaction
            $this->conn->commit();
        } catch (Exception $e) {
            // Rollback the transaction if any error occurs
            $this->conn->rollBack();
            throw new PDOException("Failed to update active terms: " . $e->getMessage());
        }
    }

    // Toggle the active status of a term
    private function toggleTermActiveStatus($termId, $status)
    {
        try {
            $query = "UPDATE {$this->table_name} SET is_active = :status WHERE period_id = :period_id";
            $stmt = $this->conn->prepare($query);

            // Bind the parameters
            $stmt->bindParam(':status', $status, PDO::PARAM_INT);
            $stmt->bindParam(':period_id', $termId, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException("Failed to toggle active status: " . $e->getMessage());
        }
    }

    // Check if an active academic year exists
    public function isAcademicYearExists($academicYear_start, $academicYear_end)
    {
        try {
            $query = "SELECT * FROM {$this->table_name} 
                      WHERE academic_year_start = :academicYear_start 
                      AND academic_year_end = :academicYear_end 
                      AND is_active = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':academicYear_start', $academicYear_start, PDO::PARAM_STR);
            $stmt->bindParam(':academicYear_end', $academicYear_end, PDO::PARAM_STR);
            $stmt->execute();

            // Return true if an active academic year exists
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new PDOException("Failed to check if academic year exists: " . $e->getMessage());
        }
    }

    // Add academic year with two semesters
    public function addAcademicYearWithSemesters($academicYear_start, $academicYear_end, $firstSemesterDates, $secondSemesterDates)
    {
        try {
            // Start a transaction for consistency
            $this->conn->beginTransaction();

            // Insert first semester
            $query = "INSERT INTO {$this->table_name} 
                      (academic_year_start, academic_year_end, semester, start_date, end_date, is_active) 
                      VALUES 
                      (:academicYear_start, :academicYear_end, '1st Semester', :firstStartDate, :firstEndDate, 1)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':academicYear_start', $academicYear_start, PDO::PARAM_STR);
            $stmt->bindParam(':academicYear_end', $academicYear_end, PDO::PARAM_STR);
            $stmt->bindParam(':firstStartDate', $firstSemesterDates['start_date'], PDO::PARAM_STR);
            $stmt->bindParam(':firstEndDate', $firstSemesterDates['end_date'], PDO::PARAM_STR);
            $stmt->execute();

            // Insert second semester
            $query = "INSERT INTO {$this->table_name} 
                      (academic_year_start, academic_year_end, semester, start_date, end_date, is_active) 
                      VALUES 
                      (:academicYear_start, :academicYear_end, '2nd Semester', :secondStartDate, :secondEndDate, 1)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':academicYear_start', $academicYear_start, PDO::PARAM_STR);
            $stmt->bindParam(':academicYear_end', $academicYear_end, PDO::PARAM_STR);
            $stmt->bindParam(':secondStartDate', $secondSemesterDates['start_date'], PDO::PARAM_STR);
            $stmt->bindParam(':secondEndDate', $secondSemesterDates['end_date'], PDO::PARAM_STR);
            $stmt->execute();

            // Commit the transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // Rollback the transaction in case of error
            $this->conn->rollBack();
            throw new PDOException("Failed to add academic year with semesters: " . $e->getMessage());
        }
    }

    // Get all academic terms
    public function getAllTerms()
    {
        try {
            $query = "SELECT period_id, academic_year_start, academic_year_end, semester, start_date, end_date, is_active 
                      FROM {$this->table_name} 
                      ORDER BY start_date DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            // Fetch and return the results
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException("Failed to get all terms: " . $e->getMessage());
        }
    }

    // Get all active academic terms
    public function getActiveTerms()
    {
        try {

            $query = "SELECT period_id, academic_year_start, academic_year_end, semester, start_date, end_date, is_active FROM academic_period WHERE is_active = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            // Fetch and return the results
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Instead of returning an error message, throw the exception to be handled elsewhere
            throw new PDOException("Failed to get active terms. <br>" . $e->getMessage());
        }
    }

    public function getCurrentPeriods()
    {
        try {
            $sql = "SELECT *
                    FROM {$this->table_name}
                    WHERE CURDATE() BETWEEN start_date AND end_date 
                    AND is_active = 1";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // Return the semester details
        } catch (PDOException $e) {
            // Log error for debugging
            error_log("Database Error: " . $e->getMessage());
            return null;
        }
    }

}

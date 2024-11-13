<?php
class AcademicTerm
{
    private $conn;
    private $table_name = "academic_calendar"; // Adjust based on your table name

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function addAcademicTerm($data)
    {
        $query = "INSERT INTO " . $this->table_name . " (academic_year, semester, start_date, end_date, is_active) VALUES (:academic_year, :semester, :start_date, :end_date, :is_active)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':academic_year', $data['academic_year']);
        $stmt->bindParam(':semester', $data['semester']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':is_active', $data['is_active'], PDO::PARAM_INT);

        return $stmt->execute(); // Return true if successful, false otherwise
    }

    // Method to deactivate current active term
    public function deactivateCurrentActiveTerm()
    {
        $query = "UPDATE " . $this->table_name . " SET is_active = 0 WHERE is_active = 1";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute(); // Return true if successful, false otherwise
    }

    public function getAllAcademicTerm()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY start_date DESC"; // Change to the appropriate field if necessary
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all terms
    }


    public function getCurrentActiveTerm()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE is_active = 1 LIMIT 1"; // Fetch the current active term
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the active term as an associative array
    }
}

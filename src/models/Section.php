<?php
class Section
{
    private $conn;
    private $table_name = "section"; // Adjust based on your table name

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function addSection($data)
    {
        // Adjust the column order in the query to match your table structure
        $query = "INSERT INTO " . $this->table_name . " (section_name, program_id, year_level, semester, section_image, adviser_id) 
                  VALUES (:section_name, :program_id, :year_level, :semester, :section_image, :adviser_id)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters in the order of the columns
        $stmt->bindParam(':section_name', $data['section_name']);
        $stmt->bindParam(':program_id', $data['program_id']);
        $stmt->bindParam(':year_level', $data['year_level']);
        $stmt->bindParam(':semester', $data['semester']);
        $stmt->bindParam(':section_image', $data['section_image'], PDO::PARAM_LOB); // Use PDO::PARAM_LOB for large objects
        $stmt->bindParam(':adviser_id', $data['adviser_id']); // adviser_id is the last column

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
}

<?php
class Section
{
    private $conn;
    private $table_name = "section";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function addSection($data)
    {
        try {
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
            $stmt->bindParam(':period_id', $data['period_id']); // Bind the dynamically determined period_id

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
            $this->conn->beginTransaction();
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
            $this->conn->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception($e->getMessage());
        }
    }
}

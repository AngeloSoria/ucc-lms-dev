<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['GeneralLogs']);

class Program
{
    private $conn;
    private $table_name = "programs"; // Adjust based on your table name
    private $generalLogsController;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();

        $this->generalLogsController = new GeneralLogsController();
    }

    // Check if program exists by program code
    public function checkProgramExists($program_code, $program_name)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE program_code = :program_code OR program_name = :program_name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':program_code', $program_code);
        $stmt->bindParam(':program_name', $program_name);
        $stmt->execute();

        return $stmt->rowCount() > 0; // Return true if the program already exists
    }


    // Add program to the database
    public function addProgram($data)
    {
        try {
            // Check if program exists
            if ($this->checkProgramExists($data['program_code'], $data['program_name'])) {
                throw new Exception("Program already exists."); // Return message if program exists
            }

            $query = "INSERT INTO " . $this->table_name . " (program_code, program_name, program_description, educational_level, program_image) 
          VALUES (:program_code, :program_name, :program_description, :educational_level, :program_image)";

            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':program_code', $data['program_code']);
            $stmt->bindParam(':program_name', $data['program_name']);
            $stmt->bindParam(':program_description', $data['program_description']);
            $stmt->bindParam(':educational_level', $data['educational_level']);
            $stmt->bindParam(':program_image', $data['program_image'], PDO::PARAM_LOB); // Use PDO::PARAM_LOB for large objects
            $stmt->execute();
            return ['success' => true, 'message' => 'Program added successfully.']; // Return true if successful, false otherwise
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Get all programs
    public function getAllPrograms()
    {
        try {
            $query = "SELECT * FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $retrievedPrograms = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as associative array

            return ['success' => true, 'data' => $retrievedPrograms];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getAllProgramsByEducationalLevel($educational_level)
    {
        try {
            $query = "SELECT program_id, program_code, program_name, program_description FROM $this->table_name WHERE educational_level = :educational_level";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":educational_level", $educational_level);
            $stmt->execute();

            $retrievedPrograms = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as associative array
            return ['success' => true, 'data' => $retrievedPrograms];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getProgramById($program_id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE program_id = :program_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':program_id', $program_id);
            $stmt->execute();

            $retrievedPrograms = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as associative array

            return ['success' => true, 'data' => $retrievedPrograms];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function updateProgram($program_data)
    {
        try {
            $query = "UPDATE $this->table_name SET 
                            program_code = :program_code, 
                            program_name = :program_name, 
                            program_description = :program_description
                        WHERE program_id = :program_id";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":program_id", $program_data['program_id']);
            $stmt->bindParam(":program_code", $program_data['program_code']);
            $stmt->bindParam(":program_name", $program_data['program_name']);
            $stmt->bindParam(":program_description", $program_data['program_description']);
            // $stmt->bindParam(":program_image", $program_data['program_image']);

            $stmt->execute();

            return ['success' => true, "message" => "Update program success!"];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function deleteProgramById($program_id)
    {
        try {
            $this->conn->beginTransaction();
            $query = "DELETE FROM $this->table_name WHERE program_id = :program_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":program_id", $program_id);
            $stmt->execute();

            // Log
            $this->generalLogsController->addLog_DELETE(
                $_SESSION['user_id'],
                $_SESSION['role'],
                "An academic program ($program_id) has been deleted."
            );
            $this->conn->commit();
            return ['success' => true, "message" => "Program has been deleted."];
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception($e->getMessage());
        }
    }
}

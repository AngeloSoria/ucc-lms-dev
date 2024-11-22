<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);

class Subject
{
    private $conn;
    private $table_name = 'subjects';
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function addSubject($subjectData)
    {
        try {
            $this->conn->beginTransaction();
            // Query to insert the user without the user_id
            $query = "INSERT INTO {$this->table_name} (subject_code, subject_name, semester, educational_level) VALUES (:subject_code, :subject_name, :semester, :educational_level)";
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':subject_code', $subjectData['subject_code']);
            $stmt->bindParam(':subject_name', $subjectData['subject_name']);
            $stmt->bindParam(':semester', $subjectData['semester']);
            $stmt->bindParam(':educational_level', $subjectData['educational_level']);


            $this->conn->commit();
            $stmt->execute();
            return ["success" => true];
        } catch (PDOException $e) {
            $this->conn->rollBack();  // Rollback the transaction if an error occurs.
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    public function getAllSubjects()
    {
        try {
            $query = "SELECT * FROM $this->table_name";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $queryResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['success' => true, 'data' => $queryResult];
        } catch (Exception $e) {
            throw new ($e->getMessage());
        }
    }

    public function checkSubjectExist($subject_code, $subject_name)
    {
        // Check if the subject exists in the database
        $query = "SELECT COUNT(*) FROM {$this->table_name} WHERE subject_code = :subject_code AND subject_name = :subject_name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':subject_code', $subject_code);
        $stmt->bindParam(':subject_name', $subject_name);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}

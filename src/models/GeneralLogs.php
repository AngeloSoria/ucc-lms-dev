<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);

class GeneralLogs
{
    private $conn;
    private $table_name = 'general_logs';
    public const ENUM_LOG_TYPES = [];

    public function __construct()
    {
        $database_instance = new Database();
        $this->conn = $database_instance->getConnection();
    }

    public function addLog_LOGIN($user_id, $user_role, $description)
    {
        try {
            // prepare
            $log_type = 'LOGIN';
            $query = "INSERT INTO {$this->table_name} (type, user_id, role, description, log_date) VALUES (:type, :user_id, :role, :description, NOW())";
            $stmt = $this->conn->prepare($query);

            // Bindings
            $stmt->bindParam(':type', $log_type);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':role', $user_role);
            $stmt->bindParam(':description', $description);

            // Execute
            $stmt->execute();
            return ['success' => true];
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function addLog_LOGOUT($user_id, $user_role = "anonymous", $description = "no description passed.")
    {
        try {
            // prepare
            $log_type = 'LOGOUT';
            $query = "INSERT INTO {$this->table_name} (type, user_id, role, description, log_date) VALUES (:type, :user_id, :role, :description, NOW())";
            $stmt = $this->conn->prepare($query);

            // Bindings
            $stmt->bindParam(':type', $log_type);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':role', $user_role);
            $stmt->bindParam(':description', $description);

            // Execute
            $stmt->execute();
            return ['success' => true];
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function addLog_UPDATEPASS($user_id, $user_role, $description)
    {
        try {
            // prepare
            $log_type = 'UPDATE';
            $query = "INSERT INTO {$this->table_name} (type, user_id, role, description, log_date) VALUES (:type, :user_id, :role, :description, NOW())";
            $stmt = $this->conn->prepare($query);

            // Bindings
            $stmt->bindParam(':type', $log_type);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':role', $user_role);
            $stmt->bindParam(':description', $description);

            // Execute
            $stmt->execute();
            return ['success' => true];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function addLog_DELETE($user_id, $user_role, $description)
    {
        try {
            // prepare
            $log_type = 'DELETE';
            $query = "INSERT INTO {$this->table_name} (type, user_id, role, description, log_date) VALUES (:type, :user_id, :role, :description, NOW())";
            $stmt = $this->conn->prepare($query);

            // Bindings
            $stmt->bindParam(':type', $log_type);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':role', $user_role);
            $stmt->bindParam(':description', $description);

            // Execute
            $stmt->execute();
            return ['success' => true];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getAllLatestLogs($limit = 100)
    {
        try {
            $query = "SELECT * FROM $this->table_name ORDER BY log_date DESC LIMIT $limit";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $logsResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $logsResult;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}

<?php
require_once(__DIR__ . '../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
class SessionLock
{

    private $conn;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function setUserSessionLock()
    {
        // Assuming session_start() has been called and the user is already authenticated
        $userId = $_SESSION['user_id'];  // User's ID from the session
        $sessionToken = session_id();  // PHP session ID or a custom session token

        // Check if there's an active session for the user
        $query = "SELECT * FROM user_session_locks WHERE user_id = :user_id AND is_locked = TRUE";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // A session is already locked for this user
            return false;
        } else {
            // No active locked session, proceed with login and lock the session
            $insertQuery = "INSERT INTO user_session_locks (user_id, session_token, is_locked) 
                    VALUES (:user_id, :session_token, TRUE)";
            $insertStmt = $this->conn->prepare($insertQuery);
            $insertStmt->bindParam(":user_id", $userId);
            $insertStmt->bindParam(":session_token", $sessionToken);
            $insertStmt->execute();

            // Proceed with the rest of the login logic
            $_SESSION['session_token'] = $sessionToken;
            return true;
        }
    }
}

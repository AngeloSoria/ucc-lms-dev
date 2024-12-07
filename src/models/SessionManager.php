<?php
require_once(__DIR__ . '../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once UTILS;

require_once(VENDOR_AUTO_LOAD);

use Dotenv\Dotenv;

class SessionManager
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();

        $dotenv = Dotenv::createImmutable(BASE_PATH);
        $dotenv->load();
    }

    public function setUserSessionLock()
    {
        try {
            // Assuming session_start() has been called and the user is already authenticated
            $userId = $_SESSION['user_id'];  // User's ID from the session
            $sessionToken = session_id();  // PHP session ID

            // Set the current time in the Philippine Time Zone (UTC+8)
            $now = new DateTime('now', new DateTimeZone('Asia/Manila'));  // Philippine Time Zone (PHT)
            $currentTime = $now->format('Y-m-d H:i:s');  // Format the time for MySQL

            // Check if the user already has an active session (locked)
            $checkQuery = "SELECT * FROM user_session_locks WHERE user_id = :user_id AND is_locked = TRUE";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(":user_id", $userId);
            $checkStmt->execute();
            $existingSession = $checkStmt->fetch(PDO::FETCH_ASSOC);

            // If the user already has a locked session, prevent login
            if ($existingSession) {
                return false;  // Another session is already active for this user
            }

            // Use a single query to insert or update the lock
            $query = "
            INSERT INTO user_session_locks (user_id, session_token, is_locked, last_activity)
            VALUES (:user_id, :session_token, TRUE, :last_activity)
            ON DUPLICATE KEY UPDATE session_token = :session_token, is_locked = TRUE, last_activity = :last_activity
        ";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $userId);
            $stmt->bindParam(":session_token", $sessionToken);
            $stmt->bindParam(":last_activity", $currentTime);
            $stmt->execute();

            // Store session token in the session for later validation
            $_SESSION['session_token'] = $sessionToken;

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }


    public function updateLastActivity($userId, $sessionId)
    {
        try {
            // Use the Philippine Time Zone (Asia/Manila) for the current time
            $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
            $currentTime = $now->format('Y-m-d H:i:s');  // Format the time as a string

            // Update the last_activity with the current time in Philippine Time Zone
            $query = "UPDATE user_session_locks SET last_activity = :current_time WHERE user_id = :user_id AND session_token = :session_token";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":current_time", $currentTime);
            $stmt->bindParam(":user_id", $userId);
            $stmt->bindParam(":session_token", $sessionId);
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle error (log it or display a message)
            return false;
        }

        return true;
    }


    public function checkSessionExpiry()
    {
        try {
            // Assuming session_start() has been called
            $sessionToken = $_SESSION['session_token'];  // Session token from the session

            // Query to get the last_activity from the database
            $query = "SELECT last_activity FROM user_session_locks WHERE session_token = :session_token";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":session_token", $sessionToken);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Get last activity time from the database and create DateTime object using Philippine time zone
                $lastActivity = new DateTime($result['last_activity'], new DateTimeZone('Asia/Manila'));

                // Get current time in Philippine Time Zone (PHT)
                $now = new DateTime('now', new DateTimeZone('Asia/Manila'));

                // Calculate the total time difference in seconds
                $timeDifference = $now->getTimestamp() - $lastActivity->getTimestamp();

                // Convert timeout to seconds
                $timeoutInSeconds = $_ENV['SESSION_EXPIRY_TIME'] * 60;

                // Check if the time difference exceeds the timeout
                if ($timeDifference > $timeoutInSeconds) {
                    return false;  // Session expired
                }
            }

            return true;  // Session is valid

        } catch (PDOException $e) {
            return false;  // Handle database errors
        }
    }

    public function userHasSession($user_id)
    {
        try {
            $query = "SELECT * FROM user_session_locks WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam("user_id", $user_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
}

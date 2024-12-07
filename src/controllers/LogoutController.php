<?php
require_once(__DIR__ . '../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['User']);
require_once(FILE_PATHS['Controllers']['GeneralLogs']);
require_once(FILE_PATHS['Functions']['PHPLogger']);

session_start();

class LogoutController
{
    public function logout()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            // If no user is logged in, redirect to login page
            header("Location: " . BASE_PATH_LINK . "/login.php");
            exit();
        }

        // Create an instance of the UserController
        $userController = new UserController();

        // Update last_login in the database (store last logout time or perform other relevant actions)
        $userController->updateLastLoginByUserId($_SESSION['user_id']);

        // Create an instance of GeneralLogsController for logging
        $generalLogsController = new GeneralLogsController();

        // Add log for the logout event
        $generalLogsController->addLog_LOGOUT($_SESSION['user_id'], $_SESSION['role'], "User has logged out from session.");

        // Get user ID and session token
        $userId = $_SESSION['user_id'];

        // Remove the session lock when logging out
        $db = new Database();
        $conn = $db->getConnection();
        $deleteQuery = "DELETE FROM user_session_locks WHERE user_id = :user_id";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bindParam(":user_id", $userId);
        $deleteStmt->execute();

        $_sessionExpired = $_SESSION['SessionExpired'];

        // Destroy session to log the user out
        session_unset();
        session_destroy();

        if ($_sessionExpired) {
            session_start();
            $_SESSION['SESSION_EXPIRED_ERR'] = true;
        }

        // Redirect to the login page after logging out
        header("Location: " . BASE_PATH_LINK);
        exit();
    }
}

// Call the logout function
$logoutController = new LogoutController();
$logoutController->logout();

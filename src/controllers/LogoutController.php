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
        // Create an instance of the UserController
        $userController = new UserController();
        // Update last_login from db
        $userController->updateLastLoginByUserId($_SESSION['user_id']);

        $generalLogsController = new GeneralLogsController();

        $generalLogsController->addLog_LOGOUT($_SESSION['user_id'], $_SESSION['role'], "User has logged out from session.");

        // Log to txt
        msgLog('LOGOUT', '[' . $_SESSION['user_id'] . '] [Log out from session]');

        // Assuming session_start() and the user is logged out
        $userId = $_SESSION['user_id'];  // Get the user ID from the session
        $sessionToken = session_id();  // PHP session ID

        // Remove the session lock when logging out
        $db = new Database();
        $conn = $db->getConnection();
        $deleteQuery = "DELETE FROM user_session_locks WHERE user_id = :user_id AND session_token = :session_token";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bindParam(":user_id", $userId);
        $deleteStmt->bindParam(":session_token", $sessionToken);
        $deleteStmt->execute();

        // Destroy session
        session_unset();
        session_destroy();

        // Redirect to login page
        header("Location: " . BASE_PATH_LINK);
        exit();
    }
}

// Call the logout function
$logoutController = new LogoutController();
$logoutController->logout();

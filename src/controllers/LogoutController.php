<?php
require_once(__DIR__ . '../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['User']);
require_once(FILE_PATHS['Functions']['PHPLogger']);



session_start();
class LogoutController
{
    public function logout()
    {
        // Create a new instance of the Database class
        $database = new Database();
        $db = $database->getConnection(); // Establish the database connection

        // Create an instance of the UserController
        $userController = new UserController($db);
        // Update last_login from db
        $userController->updateLastLoginByUserId($_SESSION['user_id']);

        // Log to txt
        msgLog('LOGOUT', '[' . $_SESSION['user_id'] . '] [Log out from session]');

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

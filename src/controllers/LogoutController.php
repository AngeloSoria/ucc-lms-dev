<?php
require_once(__DIR__ . '../../config/PathsHandler.php');

session_start();
class LogoutController
{
    public function logout()
    {
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

<?php
// ABSOLUTE ROOT_PATH
include_once $_SERVER['DOCUMENT_ROOT'] . "/ucc-lms-dev/src/config/rootpath.php";

session_start();

class LogoutController
{
    public function logout()
    {
        // Destroy session
        session_unset();
        session_destroy();

        // Redirect to login page
        header("Location: " . BASE_PATH);
        exit();
    }
}

// Call the logout function
$logoutController = new LogoutController();
$logoutController->logout();

<?php
session_start();

class LogoutController
{
    public function logout()
    {
        // Destroy session
        session_unset();
        session_destroy();

        // Redirect to login page
        header("Location: /School_LMS_2/");
        exit();
    }
}

// Call the logout function
$logoutController = new LogoutController();
$logoutController->logout();

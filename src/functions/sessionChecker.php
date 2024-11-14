<?php

function checkUserAccess($allowedRoles)
{
    // Start session if it hasn't been started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if user is logged in and has a role
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        header('Location: ' . BASE_PATH_LINK);  // Redirect if not logged in
        exit();
    }

    // Check if the user’s role is allowed on this page
    if (!in_array($_SESSION['role'], $allowedRoles)) {
        header('Location: ' . BASE_PATH_LINK);  // Redirect if role not authorized
        exit();
    }
}


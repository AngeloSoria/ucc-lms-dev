<?php
header("Content-Type: application/json");

require_once('../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Controllers']['User']);

// Validate POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPass = $_POST['password'] ?? null; // Match JavaScript key
    $confirmPass = $_POST['confirmPassword'] ?? null;
    $sessionUserId = $_POST['session_userID'] ?? null;
    $sessionUserRole = $_POST['session_userRole'] ?? null;

    // Check if all required data is provided
    if (!isset($newPass, $confirmPass, $sessionUserId, $sessionUserRole)) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Check if passwords match
    if ($newPass !== $confirmPass) {
        http_response_code(400);
        echo json_encode(['error' => 'Passwords do not match']);
        exit;
    }

    // Proceed with updating the password
    $userController = new UserController();
    $updateRequest = $userController->updateUserPassword($sessionUserId, $sessionUserRole, $newPass);
    if ($updateRequest['success']) {
        http_response_code(200);
        echo json_encode(['data' => $updateRequest['message']]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update password']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method']);
}

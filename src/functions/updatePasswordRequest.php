<?php
session_start();
require_once('../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Controllers']['User']);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'updatePassword') {
    $updatePasswordData = [
        'pass1' => $_POST['inputNewPassword'],
        'pass2' => $_POST['inputConfirmPassword'],
    ];

    // Check if passwords match
    if ($updatePasswordData['pass1'] !== $updatePasswordData['pass2']) {
        return ['success' => false, 'message' => "The passwords does not match"];
    }

    // Proceed with updating the password
    $userController = new UserController();
    $updateRequest = $userController->updateUserPassword($_SESSION['user_id'], $_SESSION['role'], $updatePasswordData['pass1']);

    $_SESSION["_ResultMessage"] = $updateRequest;

    header('Location: ' . BASE_PATH_LINK);
    exit();
}

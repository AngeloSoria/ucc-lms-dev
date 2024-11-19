<?php
require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Models']['User']);

$database = new Database();
$db = $database->getConnection();

$userModel = new User($db);

// Assuming getAllUsersByRole method fetches teachers' data
$teachers = $userModel->getAllUsersByRole('Teacher');

// Check if any teachers are returned
if ($teachers['success']) {
    $teachersList = [];

    // Loop through the results and structure them as needed
    foreach ($teachers['data'] as $teacher) {
        // Assuming $teacher contains 'user_id', 'first_name', and 'last_name'
        $teachersList[] = [
            'userid' => $teacher['user_id'],
            'username' => $teacher['first_name'] . ' ' . $teacher['last_name']
        ];
    }

    // Send the response in JSON format
    header('Content-Type: application/json');
    echo json_encode(['teachers' => $teachersList]);
} else {
    // Handle the case where no teachers are found
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No teachers found']);
}

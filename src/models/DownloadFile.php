<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Controllers']['ModuleContent']); // Include the model

if (!isset($_GET['content_id']) || empty($_GET['content_id'])) {
    die("Content ID is required.");
}

$content_id = (int) $_GET['content_id'];  // Get the content ID from the URL query parameter

try {
    $moduleContentController = new ModuleContentController();

    // Get the content file by ID
    $response = $moduleContentController->getContentFile($content_id);

    if ($response['success']) {
        $file = $response['data'];

        // Set headers for content display/download
        header("Content-Type: " . $file['mime_type']);  // Set the correct mime type
        header("Content-Disposition: inline; filename=\"" . $file['file_name'] . "\"");

        // Output the file data (the BLOB data)
        echo $file['file_data'];
    } else {
        // If no file found, send an error response
        echo json_encode(['error' => $response['message']]);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}

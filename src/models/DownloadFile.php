<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Controllers']['ModuleContent']); // Include the model
require_once UTILS;

$moduleContentController = new ModuleContentController();

if (isset($_GET['submission_files_id'])) {
    if (empty($_GET['submission_files_id'])) {
        die("Submission File ID is required.");
    }


    $submission_files_id = (int) $_GET['submission_files_id'];  // Get the content ID from the URL query parameter

    try {

        // Get the content file by ID
        $response = $moduleContentController->getFileBySubmissionFilesId($submission_files_id);

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
} elseif (isset($_GET['content_id'])) {
    if (empty($_GET['content_id'])) {
        die("Content ID is required.");
    }
    if (!isset($_GET['content_file_id']) || empty($_GET['content_file_id'])) {
        die("content_file_id is required.");
    }


    $content_id = (int) $_GET['content_id'];  // Get the content ID from the URL query parameter
    $content_file_id = (int) $_GET['content_file_id'];  // Get the content ID from the URL query parameter

    try {
        // Get the content file by ID
        $response = $moduleContentController->getFileByContentFileId($content_id, $content_file_id);

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
} else {
    die("No passed id from submission or content.");
}

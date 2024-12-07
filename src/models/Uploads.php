<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Functions']['PHPLogger']);

class Uploads
{
    private $upload_settings = [
        "upload_limit" => 200 * (1_000_000), // 200MB max upload limit
        "allowed_types" => [
            "images" => ["png", "jpg", "jpeg", "gif"],
            "video" => ["mp4"],
            "audio" => ["mp3"]
        ],
    ];

    private $upload_dir;

    private $conn;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
        $this->upload_dir = "uploads/";
    }

    public function initUserDirectoriesOnCreate($user_id)
    {
        // Uploaded Files
        $upload_user_dir = $this->upload_dir . "users/$user_id/";
        if (!is_dir($upload_user_dir)) {
            mkdir($upload_user_dir, 0777, true);
        }
    }

    public function uploadProfileImage($user_id, $file)
    {
        $target_dir = BASE_PATH_LINK . $this->upload_dir . "profile_images/";

        // Ensure the "profile_images" directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Ensure a subdirectory for the user exists
        $user_dir = $target_dir . "$user_id/";
        if (!is_dir($user_dir)) {
            mkdir($user_dir, 0777, true);
        }

        // Validate if the file is an image
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            return [
                'success' => false,
                'message' => "Error: Only JPEG, PNG, and GIF files are allowed."
            ];
        }

        // Handle file upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'message' => "File upload error: " . $file['error']
            ];
        }

        // Generate a unique filename: user_id_uniqueID.extension
        $uniqueId = uniqid();
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = $user_id . '_' . $uniqueId . '.' . $extension;

        $targetPath = $user_dir . $newFileName;

        // Move the uploaded file to the user's directory
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Construct the relative path from the BASE_PATH_LINK
            $relativePath = "src/" . $this->upload_dir . "profile_images/$user_id/" . $newFileName;

            return [
                'success' => true,
                'message' => "Image uploaded successfully!",
                'data' => $relativePath // Provide the relative path for use
            ];
        } else {
            return [
                'success' => false,
                'message' => "Error moving uploaded file."
            ];
        }
    }
}

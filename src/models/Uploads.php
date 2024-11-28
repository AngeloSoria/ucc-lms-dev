<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);

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

    private $conn;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function uploadProfielImage($user_id)
    {
        $target_dir = UPLOAD_PATH['User'] . "/profile_images";
        $user_dir = $target_dir . "/$user_id";
        if (!is_dir($user_dir)) {
            mkdir($user_dir, 0777, true);
        }
    }
}

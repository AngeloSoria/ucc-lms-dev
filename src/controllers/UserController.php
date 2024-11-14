<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Models']['User']);
require_once(FILE_PATHS['Functions']['PHPLogger']);

class UserController
{
    private $userModel;

    public function __construct($db)
    {
        $this->userModel = new User($db);
    }

    // Add user to database with checks
    public function addUser($userData)
    {
        // Check if the user already exists
        if ($this->userModel->checkUserExists($userData['username'])) {
            return ["error", "User with this username (" . $userData['username'] . ") already exists."];
        }

        // Read the profile picture file directly from $_FILES
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            // Open the file and read its contents
            $userData['profile_pic'] = file_get_contents($_FILES['profile_pic']['tmp_name']);
        } else {
            $userData['profile_pic'] = null;  // Handle cases where there's no profile picture
        }

        // Add the user and get the user_id (auto-incremented by MySQL)
        $userId = $this->userModel->addUser($userData);

        // If user creation was successful
        if ($userId !== false) {
            // If the user is a teacher, add them to the teacher_level table
            if ($userData['role'] == 'Teacher') {
                $addTeacherResult = $this->userModel->addTeacher($userId, $userData['educational_level']);
                if ($addTeacherResult !== true) {
                    return ["error", "Error adding teacher to teacher_level table."];
                }
            }

            msgLog("CRUD", "[ADD] [USER] [USERNAME: " . $userData["username"] . "] | [" . $_SESSION["username"] . "] [" . $_SESSION["role"] . "]");

            return ["success", "User added successfully!"];
        } else {
            return ["error", "Error adding user."];
        }
    }


    public function getAllUsers($limit = 100)
    {
        try {
            return $this->userModel->getAllUsers($limit);
        } catch (Exception $e) {
            return ['error', $e->getMessage()];
        }
    }
    public function getRoleCounts()
    {
        try {
            $roleCounts = $this->userModel->getRoleCounts();  // Call the model method to get counts
            return $roleCounts;
        } catch (Exception $e) {
            return ['error' => 'Failed to get role counts: ' . $e->getMessage()];
        }
    }

    public function getLatestUserId()
    {
        return $this->userModel->getLatestUserId(); // Call the model's getLatestUserId method
    }
}

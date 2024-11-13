<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');

require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Models']['User']);

class UserController
{
    private $db;
    private $userModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
    }

    public function addUser()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Sanitize and assign user data
                $userData = [
                    'user_id' => htmlspecialchars(strip_tags($_POST['user_id'])),
                    'role' => htmlspecialchars(strip_tags($_POST['role'])),
                    'first_name' => htmlspecialchars(strip_tags($_POST['first_name'])),
                    'middle_name' => htmlspecialchars(strip_tags($_POST['middle_name'])),
                    'last_name' => htmlspecialchars(strip_tags($_POST['last_name'])),
                    'gender' => htmlspecialchars(strip_tags($_POST['gender'])),
                    'dob' => htmlspecialchars(strip_tags($_POST['dob'])),
                    'username' => htmlspecialchars(strip_tags($_POST['username'])),
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT), // Hash the password
                    'email' => htmlspecialchars(strip_tags($_POST['email'])),
                    'profile_pic' => null // Initialize profile_pic to null
                ];

                // Handle profile picture upload
                if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
                    $profilePic = $_FILES['profile_pic'];
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Allowed image types

                    // Check file type
                    if (in_array($profilePic['type'], $allowedTypes)) {
                        $userData['profile_pic'] = file_get_contents($profilePic['tmp_name']); // Store the image data
                    } else {
                        throw new Exception("Invalid profile picture type.");
                    }
                }

                // Call the model to add the user
                if ($userData['role'] === 'teacher') {
                    // Add user as a teacher
                    if ($this->userModel->addTeacher($userData)) {
                        echo "Teacher added successfully.";
                    } else {
                        throw new Exception("Failed to add teacher.");
                    }
                } else {
                    // Regular user addition
                    if ($this->userModel->addUser($userData)) {
                        echo "User added successfully.";
                    } else {
                        throw new Exception("Failed to add user.");
                    }
                }
            }
        } catch (Exception $e) {
            // Handle the exception by passing the error message
            return $e->getMessage();
        }
    }

    public function addTeacher($userData)
    {
        return $this->userModel->addTeacher($userData); // Assuming userModel is already set to an instance of the User model
    }

    public function editUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get user data from the form, including the existing password and profile pic
            $userData = [
                'user_id' => htmlspecialchars(strip_tags($_POST['user_id'])),
                'role' => htmlspecialchars(strip_tags($_POST['role'])),
                'first_name' => htmlspecialchars(strip_tags($_POST['first_name'])),
                'middle_name' => htmlspecialchars(strip_tags($_POST['middle_name'])),
                'last_name' => htmlspecialchars(strip_tags($_POST['last_name'])),
                'gender' => htmlspecialchars(strip_tags($_POST['gender'])),
                'dob' => htmlspecialchars(strip_tags($_POST['dob'])),
                'username' => htmlspecialchars(strip_tags($_POST['username'])),
                'password' => !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $_POST['existing_password'],
                'existing_profile_pic' => $_POST['existing_profile_pic'], // Existing profile picture data
                'email' => htmlspecialchars(strip_tags($_POST['email'])),
            ];

            // Call the model to update the user
            if ($this->userModel->editUser($userData)) {
                // Success handling, e.g., redirect or display success message
                echo "User updated successfully.";
            } else {
                // Error handling
                echo "Failed to update user.";
            }
        }
    }
}

<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Models']['User']);
require_once(FILE_PATHS['Controllers']['GeneralLogs']);
require_once(FILE_PATHS['Functions']['PHPLogger']);

use PhpOffice\PhpSpreadsheet\IOFactory;

class UserController
{
    private $userModel;
    private $generalLogsController;

    public function __construct()
    {
        $this->userModel = new User();
        $this->generalLogsController = new GeneralLogsController();
    }

    // UPDATE USER PROFILE
    public function updateUserProfile($userId, $newData)
    {
        try {
            $updateResult = $this->userModel->updateUserInfo($userId, $newData);

            if ($updateResult['success']) {
                msgLog("UPDATE PROFILE", "[UPDATE] [USER ID: " . $userId . "] | Updated user profile.");
                return ["success" => true, "message" => "User profile updated successfully."];
            } else {
                return ["success" => false, "message" => "Failed to update user profile."];
            }
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }



    public function uploadUsersFromExcel()
    {
        if (!isset($_FILES['userFile']['tmp_name'])) {
            return ["success" => false, "message" => "No file uploaded."];
        }

        $filePath = $_FILES['userFile']['tmp_name'];

        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            msgLog("SHEET", json_encode($rows));

            $users = [];
            foreach ($rows as $index => $row) {
                if ($index == 0)
                    continue; // Skip header row

                // Auto-generate username and user_id
                $userId = uniqid(); // Or implement a custom ID generation logic
                $username = strtoupper(substr($row[0], 0, 1)) . $row[2] . "." . $userId; // JDoe.1004

                $users[] = [
                    'user_id' => $userId,
                    'username' => $username,
                    'first_name' => $row[0],
                    'middle_name' => $row[1],
                    'last_name' => $row[2],
                    'gender' => $row[3],
                    'dob' => $row[4],
                    'password' => password_hash($row[5], PASSWORD_DEFAULT), // Password from Excel file
                    'role' => $row[6],
                    'educational_level' => $row[7]
                ];
            }
            $result = $this->addMultipleUsers($users);
            // msgLog('sadada', json_encode($result));
            return $result; // Call the model to handle batch user creation
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }


    public function addMultipleUsers($usersData)
    {
        $results = []; // Store the result for each user

        foreach ($usersData as $userData) {
            // Generate user ID (auto-increment logic, e.g., get the last ID from DB and increment it)
            $userData['user_id'] = $this->userModel->generateUserId();

            // Generate username based on the user's name and user ID
            $userData['username'] = $this->generateUsername($userData['first_name'], $userData['last_name'], $userData['user_id']);

            // Check if user already exists
            if ($this->userModel->checkUserExists($userData['first_name'], $userData['last_name'], $userData['dob'])) {
                $results = [
                    "success" => false,
                    "message" => "User with similar (" . $userData['first_name'] . $userData['last_name'] . $userData['dob'] . ") already exists.",
                    "data" => $userData
                ];
                continue;
            }

            // Add the user
            $MODEL_RESULT = $this->userModel->addUser($userData);

            if ($MODEL_RESULT['success']) {
                // Add role-specific details
                if ($userData['role'] == 'Teacher') {
                    $roleResult = $this->userModel->addTeacher($userData['user_id'], $userData['educational_level']);
                } elseif ($userData['role'] == 'Student') {
                    $roleResult = $this->userModel->addStudent($userData['user_id'], $userData['educational_level']);
                } else {
                    $roleResult = ['success' => true]; // Admin or other roles may not need additional tables
                }

                if ($roleResult['success']) {
                    $results = [
                        "success" => true,
                        "message" => "User added successfully.",
                        "data" => $userData
                    ];
                } else {
                    $results = [
                        "success" => false,
                        "message" => "Failed to add role-specific data for user (" . $userData['username'] . ").",
                        "data" => $userData
                    ];
                }
            } else {
                $results = [
                    "success" => false,
                    "message" => "Failed to add user. (" . $MODEL_RESULT['message'] . ")",
                    "data" => $userData
                ];
            }
        }

        // msgLog('sadada', json_encode($results));
        return $results;
    }

    // Helper function to generate username
    private function generateUsername($firstName, $lastName, $userId)
    {
        $formattedName = strtoupper(substr($firstName, 0, 1)) . ucfirst(strtolower($lastName));

        return $formattedName . '.' . $userId;
    }


    // ADD DATA
    public function addUser($userData)
    {
        // Check if the user already exists
        if ($this->userModel->checkUserExists($userData['first_name'], $userData['last_name'], $userData['dob'])) {
            return ["success" => false, "message" => "User with this username (" . $userData['username'] . ") already exists."];
        }

        // Add the user and get the user_id (auto-incremented by MySQL)
        $MODEL_RESULT = $this->userModel->addUser($userData);

        // If user creation was successful
        if ($MODEL_RESULT['success'] == true) {
            // If the user is a teacher, add them to the teacher_level table
            if ($userData['role'] == 'Teacher') {
                $addTeacherResult = $this->userModel->addTeacher($userData['user_id'], $userData['educational_level']);
                if (!$addTeacherResult['success']) {
                    return ["success" => false, "message" => "Error adding teacher to teacher_level table."];
                }
            } else if ($userData['role'] == 'Student') {
                $addTeacherResult = $this->userModel->addStudent($userData['user_id'], $userData['educational_level']);
                if (!$addTeacherResult['success']) {
                    return ["success" => false, "message" => "Error adding teacher to teacher_level table."];
                }
            }

            msgLog("CRUD", "[ADD] [USER] [USERNAME: " . $userData["username"] . "] | [" . $_SESSION["username"] . "] [" . $_SESSION["role"] . "]");

            return [
                "success" => true,
                "message" => "User added successfully.",
                // "data" => <data_here> (situational)
            ];
        } else {
            return [
                "success" => false,
                "message" => "Something went wrong adding user. (" . $MODEL_RESULT['message'] . ")",
            ];
        }
    }

    public function deleteUser($userId)
    {
        try {
            $result = $this->userModel->deleteUser($userId);
            if ($result['success']) {
                $result["message"] = "Successfully deleted a user";
            }
            return $result;
        } catch (Exception $e) {
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    public function deleteUsers($userIds)
    {
        try {

            foreach ($userIds as $userId) {
                $result = $this->userModel->deleteUser($userId);

                // If a user deletion fails, throw an exception
                if (!$result['success']) {
                    throw new Exception("Failed to delete user with ID: $userId");
                }

                // Log the action after each deletion
                $this->generalLogsController->addLog_DELETE($_SESSION['user_id'], $_SESSION['role'], "Deleted a user with user_id: $userId");
            }

            return ['success' => true, 'message' => 'Successfully deleted selected users'];
        } catch (Exception $e) {


            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    public function getUserById($userId)
    {
        try {
            $userData = $this->userModel->getUserById($userId);
            if (!empty($userData)) {
                return [
                    "success" => true,
                    "data" => $userData
                ];
            } else {
                return [
                    "success" => false,
                    "message" => "No user with ($userId) found."
                ];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // REMOVE DATA

    // UPDATE DATA
    public function updateUserPassword($user_id, $role, $unhashed_password)
    {
        try {
            $updateRequest = $this->userModel->updateUserPassword($user_id, $unhashed_password);
            if ($updateRequest['success']) {
                $this->generalLogsController->addLog_UPDATEPASS($user_id, $role);
                msgLog("UPDATE PASS", "new pass: $unhashed_password");
                return ['success' => true, 'message' => 'Password successfully updated. good!'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateLastLoginByUserId($userId)
    {
        try {
            // Assuming $this->db is the PDO instance
            $updateRequest = $this->userModel->updateLastLoginByUserId($userId);
            if ($updateRequest['success'] == false) {
                return ['success' => false, 'message' => $updateRequest['message']];
            }
        } catch (PDOException $e) {
            // Handle any exceptions
            return ["success" => false, "message" => "Error updating last login: " . $e->getMessage()];
        }
    }


    // GET DATA
    public function getAllUsersByRole($role)
    {
        try {
            $getUsersByRole = $this->userModel->getAllUsersByRole($role);
            if ($getUsersByRole['success'] == true) {
                return ['success' => true, "data" => $getUsersByRole['data']];
            } else {
                return $getUsersByRole;
            }
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function getAllUsers($limit = 100)
    {
        try {
            $queryResult = $this->userModel->getAllUsers($limit);
            return [
                "success" => true,
                "message" => "User added successfully.",
                "data" => $queryResult
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getRoleCounts()
    {
        try {
            $roleCounts = $this->userModel->getRoleCounts();  // Call the model method to get counts
            return ['success' => true, 'data' => $roleCounts];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Failed to get role counts: ' . $e->getMessage()];
        }
    }

    public function getLatestUserId()
    {
        return $this->userModel->getLatestUserId(); // Call the model's getLatestUserId method
    }

    public function getValidRoles()
    {
        return $this->userModel->getValidRoles();
    }

    public function getAllTeachers()
    {
        // Fetch all teachers with their educational level from the model
        $teachers = $this->userModel->getAllTeachersWithEducationLevel();

        // Check if teachers are found
        if (!empty($teachers)) {
            // Pass data to the view (or handle as needed, e.g., return it as JSON)
            return [
                "success" => true,
                "data" => $teachers
            ];
        } else {
            // If no teachers are found
            return [
                "success" => false,
                "message" => "No teachers found."
            ];
        }
    }

    public function fetchSearchTeacher($searchByTableName, $query, $educationalLevel)
    {
        if ($searchByTableName === 'teacher') {
            return $this->userModel->searchTeacherByRoleAndEducationalLevel($query, $educationalLevel);
        }
        return [];
    }

    public function userRequiresPasswordReset($user_id)
    {
        try {
            $result = $this->userModel->userRequiresPasswordReset($user_id);
            return $result ? ['success' => true, 'data' => $result] : ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'data' => $e->getMessage()];
        }
    }
}

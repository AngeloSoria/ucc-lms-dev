<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Controllers']['Uploads']);
require_once(FILE_PATHS['Controllers']['GeneralLogs']);

class User
{
    private $conn;
    private $table_name = 'users';
    public const ENUM_USER_ROLES = ['admin', 'level coordinator', 'teacher', 'student'];
    private $uploadsController;

    private $generalLogsController;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();

        $this->uploadsController = new UploadsController();
        $this->generalLogsController = new GeneralLogsController();
    }
    // Generate User ID
    public function generateUserId()
    {
        try {
            $query = "SELECT MAX(user_id) AS last_id FROM {$this->table_name}";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $lastId = $result['last_id'] ?? 1000; // Start from 1001 if no users exist
            return $lastId + 1;
        } catch (PDOException $e) {
            throw new Exception("Failed to generate User ID: " . $e->getMessage());
        }
    }

    // ADD user to DATABASE
    public function addUser($userData)
    {
        try {
            $this->conn->beginTransaction(); // Begin transaction

            $query = "INSERT INTO {$this->table_name} 
                (user_id, first_name, middle_name, last_name, gender, role, dob, username, password, profile_pic) 
                VALUES 
                (:user_id, :first_name, :middle_name, :last_name, :gender, :role, :dob, :username, :password, :profile_pic)";

            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':user_id', $userData['user_id']);
            $stmt->bindParam(':role', $userData['role']);
            $stmt->bindParam(':first_name', $userData['first_name']);
            $stmt->bindParam(':middle_name', $userData['middle_name']);
            $stmt->bindParam(':last_name', $userData['last_name']);
            $stmt->bindParam(':gender', $userData['gender']);
            $stmt->bindParam(':dob', $userData['dob']);
            $stmt->bindParam(':username', $userData['username']);
            $stmt->bindParam(':password', $userData['password']);
            $stmt->bindParam(':profile_pic', $userData['profile_pic'], PDO::PARAM_LOB); // Binary data

            $stmt->execute(); // Execute statement
            $this->conn->commit(); // Commit transaction

            $this->generalLogsController->addLog_CREATE($_SESSION['user_id'], $_SESSION['role'], "Added a user with user_id of " . $userData['user_id']);

            return ["success" => true];
        } catch (PDOException $e) {
            $this->conn->rollBack(); // Rollback transaction on error
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    // Add teacher role
    public function addTeacher($userId, $educational_level)
    {
        try {
            $query = "INSERT INTO educational_level (user_id, educational_level) VALUES (:user_id, :educational_level)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':educational_level', $educational_level);
            $stmt->execute();

            $this->generalLogsController->addLog_CREATE($_SESSION['user_id'], $_SESSION['role'], "Enrolled a user as a teacher" . $userId);

            return ['success' => true, 'message' => 'User added successfully.'];
        } catch (PDOException $e) {
            return ["success" => false, 'message' => $e->getMessage()];
        }
    }
    public function addStudent($userId, $educational_level)
    {
        try {
            $query = "INSERT INTO educational_level (user_id, educational_level) VALUES (:user_id, :educational_level)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':educational_level', $educational_level);
            $stmt->execute();

            $this->generalLogsController->addLog_CREATE($_SESSION['user_id'], $_SESSION['role'], "Enrolled a user as a student (" . $userId . ")");

            return ['success' => true, 'message' => 'User added successfully.'];
        } catch (PDOException $e) {
            return ["success" => false, 'message' => $e->getMessage()];
        }
    }

    public function deleteUser($userId)
    {
        try {
            $this->conn->beginTransaction();

            $query = "DELETE FROM {$this->table_name} WHERE user_id = :user_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $userId);
            $stmt->execute();

            $this->generalLogsController->addLog_DELETE($_SESSION['user_id'], $_SESSION['role'], "Deleted a user with user_id of $userId");
            $this->conn->commit();

            return ['success' => true];
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function getAllUsersByRole($role)
    {
        try {
            $query = "SELECT * FROM users WHERE role = :role ORDER BY user_id DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            $stmt->execute();

            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($users) <= 0) {
                return ['success' => false, "message" => "No users found with role ($role)"];
            } else {
                return ['success' => true, "data" => $users];
            }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
    // Check if user exists by email or username
    public function checkUserExists($first_name, $last_name, $dob)
    {
        // Check if the user already exists in the database
        $query = "SELECT * FROM users WHERE first_name = :first_name AND last_name = :last_name AND dob = :dob";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':dob', $dob);
        $stmt->execute();
        return count($stmt->fetchAll(PDO::FETCH_ASSOC)) > 0;
    }

    public function userRequiresPasswordReset($user_id)
    {
        try {
            $query = "SELECT requirePasswordReset FROM $this->table_name WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return isset($result['requirePasswordReset']) && $result['requirePasswordReset'] == 1;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function updateUserPassword($user_id, $password)
    {
        try {
            // Hash the password before storing it in the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $this->conn->beginTransaction();
            $query = "UPDATE $this->table_name SET password = :password, requirePasswordReset = 0, updated_at = NOW() WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            // Commit the transaction
            $this->conn->commit();

            return ['success' => true];
        } catch (Exception $e) {
            // Roll back transaction in case of error
            $this->conn->rollBack();

            // Optionally log the error message or handle it further
            // error_log($e->getMessage());

            throw new Exception("Failed to update password: " . $e->getMessage());
        }
    }


    public function getAllUsers($limit)
    {
        try {
            // Use a placeholder :limit for the limit value
            $query = "SELECT * FROM users ORDER BY user_id DESC LIMIT :limit OFFSET 0";
            $stmt = $this->conn->prepare($query);

            // Bind the $limit parameter to the :limit placeholder
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();

            // Fetch and return the results
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Failed to get all users: " . $e->getMessage());
        }
    }

    public function getUserById($userId)
    {
        try {
            // Use a placeholder :limit for the limit value
            $query = "SELECT * FROM users WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error retrieving user: " . $e->getMessage());
        }
    }

    public function updateLastLoginByUserId($userId)
    {
        try {
            // Prepare the SQL query
            $query = "UPDATE users SET last_login = NOW() WHERE user_id = :user_id";

            // Assuming $this->db is the PDO instance
            $stmt = $this->conn->prepare($query);

            // Bind the parameter
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();

            // Optional: Check if rows were affected
            if ($stmt->rowCount() > 0) {
                return ['success' => true, 'message' => "Last login updated successfully."];
            } else {
                return ['success' => false, 'message' => "No rows updated. User ID may not exist."];
            }
        } catch (PDOException $e) {
            // Handle any exceptions
            throw new Exception($e->getMessage());
        }
    }

    // Get the latest user ID for auto-incrementing
    public function getLatestUserId()
    {
        $query = "SELECT user_id FROM {$this->table_name} ORDER BY user_id DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return isset($row['user_id']) ? $row['user_id'] + 1 : 1;
    }
    // Get the count of users per role
    public function getRoleCounts()
    {
        $query = "SELECT role, COUNT(*) AS role_count FROM {$this->table_name} GROUP BY role";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Fetch all results as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getValidRoles()
    {
        return self::ENUM_USER_ROLES;
    }

    public function getAllTeachersWithEducationLevel()
    {
        try {
            // SQL query to join educational_level and users tables
            $query = "
            SELECT 
                u.user_id,
                u.first_name,
                u.middle_name,
                u.last_name,
                u.dob,
                u.gender,
                u.role,
                u.username,
                u.profile_pic,
                u.status,
                u.created_at,
                u.updated_at,
                u.requirePasswordReset,
                tel.educational_level
            FROM 
                educational_level tel
            JOIN 
                users u ON u.user_id = tel.user_id
        ";

            // Prepare the statement
            $stmt = $this->conn->prepare($query);

            // Execute the statement
            $stmt->execute();

            // Fetch all the results as an associative array
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result ? $result : []; // Return result or an empty array if no records found
        } catch (PDOException $e) {
            throw new PDOException("Failed to get all terms: " . $e->getMessage());
        }
    }

    public function searchTeacherByRoleAndEducationalLevel($query, $educationalLevel)
    {
        $searchQuery = "%{$query}%";
        $query = "
    SELECT u.user_id, CONCAT(u.first_name, ' ', u.last_name) AS name 
    FROM users u
    JOIN educational_level tu ON u.user_id = tu.usi meaer_id
    WHERE tu.educational_level = :educational_level 
      AND u.role = 'Teacher'
      AND (u.first_name LIKE :searchQuery OR u.last_name LIKE :searchQuery)
";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':educational_level', $educationalLevel);
        $stmt->bindParam(':searchQuery', $searchQuery);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchSections($query, $role)
    {
        // Fetch the active period_id from academic_period where is_active = 1
        $activePeriodQuery = "SELECT period_id FROM academic_period WHERE is_active = 1 LIMIT 1";
        $activeStmt = $this->conn->prepare($activePeriodQuery);
        $activeStmt->execute();
        $activePeriod = $activeStmt->fetch(PDO::FETCH_ASSOC);

        if (!$activePeriod) {
            // If no active period is found, return an empty array
            return [];
        }

        $activePeriodId = $activePeriod['period_id'];

        // Base query
        $baseQuery = "SELECT section.section_id, section.section_name AS name";

        // Dynamic joins and conditions based on role
        $joinClause = "";
        $whereClause = " WHERE section.section_name LIKE :query AND section.period_id = :active_period_id";

        if ($role === 'Teacher') {
            $joinClause = " LEFT JOIN teacher ON section.teacher_id = teacher.teacher_id";
            $baseQuery .= ", teacher.teacher_name";
        } elseif ($role === 'Student') {
            $joinClause = " LEFT JOIN student_section ON section.section_id = student_section.section_id
                        LEFT JOIN student ON student_section.student_id = student.student_id";
            $baseQuery .= ", COUNT(student.student_id) AS student_count";
            $whereClause .= " GROUP BY section.section_id"; // Ensure grouping if counting students
        }

        // Combine the query
        $finalQuery = $baseQuery . " FROM section" . $joinClause . $whereClause;

        $searchQuery = "%{$query}%";
        $stmt = $this->conn->prepare($finalQuery);
        $stmt->bindParam(':query', $searchQuery);
        $stmt->bindParam(':active_period_id', $activePeriodId);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUserInfo($userId, $userData)
    {
        try {
            // Begin transaction
            $this->conn->beginTransaction();

            // Prepare the SQL query with only the fields that need to be updated
            $query = "
            UPDATE {$this->table_name} 
            SET 
                first_name = :first_name,
                middle_name = :middle_name,
                last_name = :last_name,
                gender = :gender,
                dob = :dob,
                status = :status,
                requirePasswordReset = :requirePasswordReset,
                updated_at = NOW()";

            // Add password to query if provided in $userData
            if (!empty($userData['password'])) {
                $query .= ", password = :password";
            }

            $query .= " WHERE user_id = :user_id";

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':first_name', $userData['first_name']);
            $stmt->bindParam(':middle_name', $userData['middle_name']);
            $stmt->bindParam(':last_name', $userData['last_name']);
            $stmt->bindParam(':gender', $userData['gender']);
            $stmt->bindParam(':dob', $userData['dob']);
            $stmt->bindParam(':status', $userData['status']);
            $stmt->bindParam(':requirePasswordReset', $userData['requirePasswordReset'], PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

            // Bind password if it's provided in $userData
            if (!empty($userData['password'])) {
                $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
                $stmt->bindParam(':password', $hashedPassword);
            }

            // Execute the statement
            $stmt->execute();

            $this->generalLogsController->addLog_UPDATE($_SESSION['user_id'], $_SESSION['role'], "Updated the user data of " . $userId);

            // Commit the transaction
            $this->conn->commit();

            return ['success' => true, 'message' => 'User information updated successfully.'];
        } catch (PDOException $e) {
            // Rollback transaction on error
            $this->conn->rollBack();
            return ['success' => false, 'message' => 'Failed to update user information: ' . $e->getMessage()];
        }
    }
}
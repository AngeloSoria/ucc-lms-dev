<?php
class User
{
    private $conn;
    private $table_name = 'users';
    public const ENUM_USER_ROLES = ['admin', 'level coordinator', 'teacher', 'student'];

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ADD user to DATABASE
    public function addUser($userData)
    {
        try {
            $this->conn->beginTransaction();
            // Query to insert the user without the user_id
            $query = "INSERT INTO {$this->table_name} (user_id, role, first_name, middle_name, last_name, gender, dob, username, password, profile_pic) VALUES (:user_id, :role, :first_name, :middle_name, :last_name, :gender, :dob, :username, :password, :profile_pic)";
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
            $stmt->bindParam(':profile_pic', $userData['profile_pic'], PDO::PARAM_LOB);  // For binary data

            $this->conn->commit();
            $stmt->execute();
            return ["success" => true];
        } catch (PDOException $e) {
            $this->conn->rollBack();  // Rollback the transaction if an error occurs.
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    // Add teacher role
    public function addTeacher($userId, $educational_level)
    {
        try {
            $query = "INSERT INTO teacher_educational_level (user_id, educational_level) VALUES (:user_id, :educational_level)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':educational_level', $educational_level);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return ["error", $e->getMessage()];
        }
    }

    public function getAllUsersByRole($role)
    {
        try {
            $query = "SELECT * FROM users WHERE role = :role";
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
    public function checkUserExists($username)
    {
        // Check if the user already exists in the database
        $query = "SELECT COUNT(*) FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function getAllUsers($limit)
    {
        try {
            // Use a placeholder :limit for the limit value
            $query = "SELECT * FROM users LIMIT :limit OFFSET 0";
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
            // SQL query to join teacher_educational_level and users tables
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
                teacher_educational_level tel
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
}

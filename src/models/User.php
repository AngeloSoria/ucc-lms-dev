<?php
class User
{
    private $conn;
    private $table_name = 'users';

    public function __construct($db)
    {
        $this->conn = $db;
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

    // Add user to the database
    public function addUser($userData)
    {
        // Query to insert the user without the user_id
        $query = "INSERT INTO users (user_id, role, first_name, middle_name, last_name, gender, dob, username, password, profile_pic) 
                  VALUES (:user_id, :role, :first_name, :middle_name, :last_name, :gender, :dob, :username, :password, :profile_pic)";
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

        // Execute the query
        if ($stmt->execute()) {
            // Get the last inserted user_id
            return $this->conn->lastInsertId();
        } else {
            return false;  // If insertion fails
        }
    }


    public function getAllUsers($limit)
    {
        // Use a placeholder :limit for the limit value
        $query = "SELECT user_id, first_name, middle_name, last_name, role, gender, dob, status FROM users LIMIT :limit OFFSET 0";
        $stmt = $this->conn->prepare($query);

        // Bind the $limit parameter to the :limit placeholder
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Fetch and return the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Edit user information

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
}

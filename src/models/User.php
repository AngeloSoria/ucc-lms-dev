<?php
class User
{
    private $db;
    private $table_name = 'users'; // Define table name here

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function addUser($userData)
    {
        // Prepare the SQL query with the correct number of placeholders
        $query = "INSERT INTO users (user_id, role, first_name, middle_name, last_name, gender, dob, username, password, email, profile_pic) 
                  VALUES (:user_id, :role, :first_name, :middle_name, :last_name, :gender, :dob, :username, :password, :email, :profile_pic)";

        $stmt = $this->db->prepare($query);

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
        $stmt->bindParam(':email', $userData['email']);

        // Always bind the profile_pic parameter, even if it's null
        $stmt->bindParam(':profile_pic', $userData['profile_pic'], PDO::PARAM_LOB);

        try {
            return $stmt->execute(); // Execute the query
        } catch (PDOException $e) {
            // Log the error message for debugging
            error_log("Database error: " . $e->getMessage());
            return false; // Return false on error
        }
    }

    public function editUser($userData)
    {
        $query = "UPDATE users 
                  SET role = :role, 
                      first_name = :first_name, 
                      middle_name = :middle_name, 
                      last_name = :last_name, 
                      gender = :gender, 
                      dob = :dob, 
                      username = :username, 
                      password = :password, 
                      email = :email, 
                      profile_pic = :profile_pic 
                  WHERE user_id = :user_id";

        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':user_id', $userData['user_id']);
        $stmt->bindParam(':role', $userData['role']);
        $stmt->bindParam(':first_name', $userData['first_name']);
        $stmt->bindParam(':middle_name', $userData['middle_name']);
        $stmt->bindParam(':last_name', $userData['last_name']);
        $stmt->bindParam(':gender', $userData['gender']);
        $stmt->bindParam(':dob', $userData['dob']);
        $stmt->bindParam(':username', $userData['username']);

        // Hash the password if provided
        $stmt->bindParam(':password', $userData['password']);

        // Handle file upload for profile_pic
        if (isset($userData['profile_pic']) && $userData['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $profilePic = $userData['profile_pic'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Allowed image types

            // Check file type
            if (in_array($profilePic['type'], $allowedTypes)) {
                $profilePicData = file_get_contents($profilePic['tmp_name']);
                $stmt->bindParam(':profile_pic', $profilePicData, PDO::PARAM_LOB); // Use PDO::PARAM_LOB for BLOB data
            } else {
                return false; // Invalid file type
            }
        } else {
            // Retain the existing profile picture if there is no new upload
            $stmt->bindParam(':profile_pic', $userData['existing_profile_pic'], PDO::PARAM_LOB);
        }

        return $stmt->execute();
    }

    public function getUserById($user_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);

        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            error_log("Error finding user by user_id: " . implode(", ", $stmt->errorInfo()));
            return false;
        }
    }

    public function findByUsername($username)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);

        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            error_log("Error finding user by username: " . implode(", ", $stmt->errorInfo()));
            return false;
        }
    }

    // In UserController.php
    public function getLatestUserId()
    {
        $query = "SELECT MAX(user_id) as latest_id FROM users"; // Adjust table name if different
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['latest_id'] + 1; // Increment to get the next ID
    }


    // You can add more methods for updating, deleting, and retrieving users as needed.
}

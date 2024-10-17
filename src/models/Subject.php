<?php
class User
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function addSubject($userData)
    {
        // Prepare the SQL query with the correct number of placeholders
        $query = "INSERT INTO users (user_id, role, first_name, middle_name, last_name, gender, dob, username, password, email, profile_pic) 
                  VALUES (:user_id, :role, :first_name, :middle_name, :last_name, :gender, :dob, :username, :password, :email, :profile_pic)";

        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':user_id', $userData['user_id']);
        $stmt->bindParam(':role', $userData['role']);
        $stmt->bindParam(':first_name', $userData['first_name']);

        try {
            return $stmt->execute(); // Execute the query
        } catch (PDOException $e) {
            // Log the error message for debugging
            error_log("Database error: " . $e->getMessage());
            return false; // Return false on error
        }
    }

    // You can add more methods for updating, deleting, and retrieving subject as needed.
}

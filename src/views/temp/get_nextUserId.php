<?php
// Include the database connection
include 'db.php'; // Ensure this points to your db.php

// Fetch the next User ID (auto-increment)
$result = $conn->query("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'ucc_db' AND TABLE_NAME = 'users'"); // Use your database and table names
$row = $result->fetch_assoc();

$nextUserId = $row['AUTO_INCREMENT'];

echo json_encode(["userId" => $nextUserId]);
$conn->close();
?>

<?php
require_once(__DIR__ . '/School_LMS_4/src/config/connection.php');

$database = new Database();
$pdo = $database->getConnection();

$role = "Teacher";

try {
    // Prepare the query to get teachers' data
    $stmt = $pdo->prepare("SELECT user_id, first_name, last_name FROM users WHERE role = :role");
    $stmt->bindParam(':role', $role);
    $stmt->execute();

    // Initialize the options for the dropdown
    $options = '<option value="" disabled selected>Select Adviser</option>';

    // Loop through each teacher and build the dropdown option
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $options .= '<option value="' . $row['user_id'] . '">' . $row['first_name'] . ' ' . $row['last_name'] . '</option>';
    }

    echo $options; // Output the options to the frontend
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

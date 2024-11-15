<?php
require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);

$database = new Database();
$pdo = $database->getConnection();

if (isset($_POST['educational_level'])) {
    $educationalLevel = $_POST['educational_level'];

    try {
        // Prepare the query to get advisers' data based on educational level
        $stmt = $pdo->prepare("
            SELECT u.user_id, u.first_name, u.last_name
            FROM users u 
            JOIN teacher_educational_level tu ON u.user_id = tu.user_id 
            WHERE tu.educational_level = :educational_level AND u.role = 'Teacher'
        ");
        $stmt->bindParam(':educational_level', $educationalLevel);
        $stmt->execute();

        // Start output buffering
        ob_start();

        // Initialize options for the dropdown
        $options = '<option value="" disabled selected>Select Adviser</option>';

        // Loop through each adviser and build the dropdown options
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $options .= '<option value="' . htmlspecialchars($row['user_id']) . '">'
                . htmlspecialchars($row['first_name'] . ' ' . $row['last_name'])
                . '</option>';
        }

        // Clear output buffer and output the options directly
        ob_end_clean();
        echo $options;
    } catch (PDOException $e) {
        // In case of error, send JSON error response
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
}

<?php
require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);

if (isset($_POST['educational_level'])) {
    $level = $_POST['educational_level'];

    $database = new Database();
    $pdo = $database->getConnection();

    try {
        // Prepare the query to fetch programs based on educational level
        $stmt = $pdo->prepare("SELECT program_id, program_code FROM programs WHERE educational_level = :level");
        $stmt->execute([':level' => $level]);

        // Check if any records were returned
        if ($stmt->rowCount() > 0) {
            // Start the options list
            $options = '<option value="" disabled selected>Select Program</option>';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $options .= '<option value="' . $row['program_id'] . '">' . htmlspecialchars($row['program_code']) . '</option>';
            }
            echo $options; // Return the options HTML
        } else {
            echo '<option value="" disabled>No Programs Available</option>';
        }
    } catch (PDOException $e) {
        // If there's an error with the database connection or query, return an error message
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No educational level provided']);
}

<?php
require '../../../../src/config/connection.php';

if (isset($_POST['educational_level'])) {
    $level = $_POST['educational_level'];

    $database = new Database();
    $pdo = $database->getConnection();

    try {
        $stmt = $pdo->prepare("SELECT program_id, program_name FROM programs WHERE educational_level = :level");
        $stmt->execute([':level' => $level]);

        // Ensure options are sent as <option> tags
        $options = '<option value="" disabled selected>Select Program</option>';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $options .= '<option value="' . $row['program_id'] . '">' . htmlspecialchars($row['program_name']) . '</option>'; // Use htmlspecialchars to prevent XSS
        }
        echo $options;
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

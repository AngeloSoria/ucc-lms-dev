<?php
require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Functions']["PHPLogger"]);


if (isset($_POST['educational_level'])) {
    msgLog("FETCH PROGRAMS", "POST");
    $level = $_POST['educational_level'];

    $database = new Database();
    $pdo = $database->getConnection();

    try {
        $stmt = $pdo->prepare("SELECT program_id, program_code FROM programs WHERE educational_level = :level");
        $stmt->execute([':level' => $level]);

        // Ensure options are sent as <option> tags
        $options = '<option value="" disabled selected>Select Program</option>';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $options .= '<option value="' . $row['program_id'] . '">' . htmlspecialchars($row['program_code']) . '</option>'; // Use htmlspecialchars to prevent XSS
        }
        echo $options;
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

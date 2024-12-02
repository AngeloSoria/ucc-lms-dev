<?php
require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);

$database = new Database();
$pdo = $database->getConnection();

if (isset($_POST['educational_level'])) {
    $educationalLevel = $_POST['educational_level'];
    $searchQuery = isset($_POST['query']) ? trim($_POST['query']) : '';

    try {
        if (empty($searchQuery)) {
            // If the search query is empty, return an empty response
            header('Content-Type: application/json');
            echo json_encode([]);
            exit;
        }

        // Prepare the query to get advisers' data based on educational level and search query
        $stmt = $pdo->prepare("
            SELECT u.user_id, u.first_name, u.last_name
            FROM users u 
            JOIN educational_level tu ON u.user_id = tu.user_id
            WHERE tu.educational_level = :educational_level 
            AND u.role = 'Teacher' 
            AND (u.first_name LIKE :searchQuery OR u.last_name LIKE :searchQuery)
        ");
        $stmt->bindParam(':educational_level', $educationalLevel, PDO::PARAM_STR);
        $stmt->bindValue(':searchQuery', '%' . $searchQuery . '%', PDO::PARAM_STR);
        $stmt->execute();

        $advisers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the results as a JSON response
        header('Content-Type: application/json');
        echo json_encode($advisers);
    } catch (PDOException $e) {
        // Log the error and return a JSON error response
        error_log('Database error: ' . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to fetch advisers. Please try again later.']);
    }
} else {
    // Handle missing required POST data
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request. Missing educational level.']);
}

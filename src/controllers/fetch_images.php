<?php
require '../config/connection.php'; // Adjusted to the correct path

header('Content-Type: application/json');

// Create a new PDO instance using your DBConnection class
try {
    $db = new Database(); // Create a new instance of the Database class
    $pdo = $db->getConnection(); // Get the PDO connection

    // Query to get images
    $stmt = $pdo->query("SELECT image_name, image_data FROM carousel_images ORDER BY created_at ASC");
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if images were retrieved
    if ($images) {
        // Convert image data to base64 if necessary
        foreach ($images as &$image) {
            if (!empty($image['image_data'])) {
                // Convert binary image data to base64 (if stored as binary)
                $image['image_data'] = base64_encode($image['image_data']);
                $image['image_data'] = 'data:image/jpeg;base64,' . $image['image_data'];
            } else {
                $image['image_data'] = ''; // Provide a fallback for empty images
            }
        }

        // Output the images as JSON
        echo json_encode($images);
    } else {
        // If no images are found
        echo json_encode(['error' => 'No images found in the database.']);
    }
} catch (PDOException $e) {
    // If a query or database error occurs, return it as JSON
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

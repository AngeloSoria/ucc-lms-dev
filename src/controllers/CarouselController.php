<?php
// ABSOLUTE ROOT_PATH
include_once $_SERVER['DOCUMENT_ROOT'] . "/ucc-lms-dev/src/config/rootpath.php";

// Adjust the path to the Carousel model based on your project structure
include_once ROOT_PATH . 'src/config/connection.php'; // Adjust the path accordingly
include_once ROOT_PATH . 'src/models/Carousel.php'; // Adjust the path accordingly

class CarouselController
{
    private $db;
    private $carouselModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->carouselModel = new Carousel($this->db);
    }

    public function addCarouselItem()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and assign carousel data
            $carouselData = [
                'title' => htmlspecialchars(strip_tags($_POST['title'])),
                'view_type' => htmlspecialchars(strip_tags($_POST['view_type'])),
                'image' => null // Initialize image to null
            ];

            // Handle carousel image upload
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $carouselImage = $_FILES['file'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Allowed image types

                // Check file type
                if (in_array($carouselImage['type'], $allowedTypes)) {
                    $carouselData['image'] = file_get_contents($carouselImage['tmp_name']); // Store the image data
                } else {
                    echo "Invalid carousel image type.";
                    return; // Early exit
                }
            } else {
                echo "No image uploaded or there was an upload error.";
                return; // Early exit
            }

            // Call the model to add the carousel item
            if ($this->carouselModel->addCarouselItem($carouselData)) {
                // Success handling, e.g., redirect or display success message
                header('Location: ../carousel_admin.php?success=1');
            } else {
                // Error handling
                echo "Failed to add carousel item.";
            }
        }
    }

    public function getAllCarouselItems()
    {
        return $this->carouselModel->getAllCarouselItems(); // Get carousel items from model
    }
}

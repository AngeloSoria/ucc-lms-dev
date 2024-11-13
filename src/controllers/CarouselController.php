<?php

require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Models']['Carousel']);

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
                'image' => null, // Initialize image to null
                'is_selected' => 1 // New carousel items will be selected by default
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

            // Ensure only 4 images are selected at any given time
            $selectedItems = $this->carouselModel->getSelectedItemsCount($carouselData['view_type']);
            if ($selectedItems >= 4) {
                // Update the oldest selected item to unselected if there are already 4 selected items
                $this->carouselModel->deselectOldestItem($carouselData['view_type']);
            }

            // Call the model to add the new carousel item
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

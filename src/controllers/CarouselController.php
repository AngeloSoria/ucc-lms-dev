<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Models']['Carousel']);

class CarouselController
{

    private $carouselModel;

    public function __construct($db)
    {
        $this->carouselModel = new Carousel($db);
    }

    // Method to add a new carousel item
    public function addCarouselItem()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'addCarousel') {
            // Sanitize and assign carousel data
            $carouselData = [
                'title' => htmlspecialchars(strip_tags($_POST['title'])),
                'image_path' => null, // Initialize image path to null
                'view_type' => htmlspecialchars(strip_tags($_POST['view_type'])),
                'is_selected' => 1 // New carousel items are selected by default
            ];

            // Handle carousel image upload
            $carouselData['image_path'] = $this->uploadCarouselImage($_FILES['file']);

            if (!$carouselData['image_path']) {
                echo "Error: Image upload failed.";
                return;
            }

            // Ensure only 4 images are selected at any given time
            $selectedItems = $this->carouselModel->getSelectedItemsCount($carouselData['view_type']);
            if ($selectedItems >= 4) {
                $this->carouselModel->deselectOldestItem($carouselData['view_type']);
            }

            // Call the model to add the new carousel item
            if ($this->carouselModel->addCarouselItem($carouselData)) {
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Error: Failed to add carousel item.";
            }
        }
    }

    // Method to update an existing carousel item
    // public function updateCarouselItem()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'updateCarousel') {
    //         $id = $_POST['id'];
    //         $carouselData = [
    //             'title' => htmlspecialchars(strip_tags($_POST['title'])),
    //             'view_type' => htmlspecialchars(strip_tags($_POST['view_type'])),
    //             'image_path' => $this->uploadCarouselImage($_FILES['file'], false), // Optional new image
    //             'is_selected' => isset($_POST['is_selected']) ? (int) $_POST['is_selected'] : 0
    //         ];

    //         // Use existing image path if a new image wasn't uploaded
    //         if (!$carouselData['image_path']) {
    //             $carouselData['image_path'] = $_POST['existing_image_path'];
    //         }

    //         if ($this->carouselModel->updateCarouselItem($id, $carouselData)) {
    //             header('Location: ../carousel_admin.php?updated=1');
    //         } else {
    //             echo "Error: Failed to update carousel item.";
    //         }
    //     }
    // }

    // Method to delete a carousel item by ID
    public function deleteCarouselItem()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deleteCarousel') {
            $id = $_POST['id'];

            if ($this->carouselModel->deleteCarouselItem($id)) {
                header('Location: ../carousel_admin.php?deleted=1');
            } else {
                echo "Error: Failed to delete carousel item.";
            }
        }
    }

    // Method to fetch all carousel items (for display purposes)
    public function getAllCarouselItems()
    {
        return $this->carouselModel->getAllCarouselItems();
    }

    // Private method to handle carousel image upload
    private function uploadCarouselImage($file, $required = true)
    {
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($file['type'], $allowedTypes)) {
                $uploadDir = 'src/uploads/carousel/';

                if (!is_dir('../../../../' . $uploadDir)) {
                    mkdir('../../../../' . $uploadDir, 0777, true);
                }

                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filePath = $uploadDir . uniqid('carousel_', true) . '.' . $extension;

                if (move_uploaded_file($file['tmp_name'], '../../../../' . $filePath)) {
                    return $filePath;
                }
            } else {
                echo "Error: Invalid image type.";
                return null;
            }
        } elseif ($required) {
            echo "Error: No image uploaded.";
            return null;
        }

        return null;
    }
}

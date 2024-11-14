<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Models']['Section']);

class SectionController
{
    private $db;
    private $sectionModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->sectionModel = new Section($this->db);
    }

    public function addSection()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and assign section data
            $sectionData = [
                'section_name' => htmlspecialchars(strip_tags($_POST['section_name'])),
                'educational_level' => htmlspecialchars(strip_tags($_POST['educational_level'])),
                'program_id' => htmlspecialchars(strip_tags($_POST['program_id'])),
                'year_level' => htmlspecialchars(strip_tags($_POST['year_level'])),
                'semester' => htmlspecialchars(strip_tags($_POST['semester'])),
                'section_image' => null, // Initialize section_image to null
                'adviser_id' => htmlspecialchars(strip_tags($_POST['adviser_id']))
            ];

            // Handle section image upload
            if (isset($_FILES['section_image']) && $_FILES['section_image']['error'] === UPLOAD_ERR_OK) {
                $sectionImage = $_FILES['section_image'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Allowed image types

                // Check file type
                if (in_array($sectionImage['type'], $allowedTypes)) {
                    $sectionData['section_image'] = file_get_contents($sectionImage['tmp_name']); // Store the image data
                } else {
                    return ["error", "Invalid section image type."];
                }
            }

            // Call the model to add the section
            if ($this->sectionModel->addSection($sectionData)) {
                // Success handling, e.g., redirect or display success message
                header('Location: ../section_admin.php?success=1');
            } else {
                // Error handling
                return ["error", "Failed to add section."];
            }
        }
    }

    public function getAllSections()
    {
        return $this->sectionModel->getAllSections(); // Get sections from model
    }
}

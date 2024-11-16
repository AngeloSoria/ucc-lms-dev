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

    /**
     * Handles adding a section via POST request.
     */
    public function addSection()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and assign section data
            $sectionData = [
                'section_name' => htmlspecialchars(strip_tags($_POST['section_name'])),
                'program_id' => htmlspecialchars(strip_tags($_POST['program_id'])),
                'year_level' => htmlspecialchars(strip_tags($_POST['year_level'])),
                'semester' => htmlspecialchars(strip_tags($_POST['semester'])),
                'section_image' => null, // Default to null unless provided
                'adviser_id' => htmlspecialchars(strip_tags($_POST['adviser_id']))
            ];

            // Handle section image upload
            if (isset($_FILES['section_image']) && $_FILES['section_image']['error'] === UPLOAD_ERR_OK) {
                $sectionImage = $_FILES['section_image'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Define allowed image MIME types

                // Check file type
                if (in_array($sectionImage['type'], $allowedTypes)) {
                    $sectionData['section_image'] = file_get_contents($sectionImage['tmp_name']); // Store binary image data
                } else {
                    // Return error for invalid image type
                    return ["error" => "Invalid section image type. Allowed types are: JPEG, PNG, GIF."];
                }
            }

            // Check if the section already exists
            if ($this->sectionModel->sectionExists($sectionData)) {
                return ["error" => "The section already exists. Please verify the information."];
            }

            // Add the section using the model
            if ($this->sectionModel->addSection($sectionData)) {
                // Redirect to the section admin page on success
                header('Location: ../section_admin.php?success=1');
                exit;
            } else {
                // Return error if the addition fails
                return ["error" => "Failed to add section. Please try again."];
            }
        }
        return ["error" => "Invalid request method."];
    }

    /**
     * Retrieves all sections using the model.
     */
    public function getAllSections()
    {
        return $this->sectionModel->getAllSections(); // Fetch all sections from the model
    }

    public function updateAcademicPeriod()
    {
        return $this->sectionModel->updateAcademicPeriod(); // Fetch all sections from the model
    }
}

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
                'adviser_id' => htmlspecialchars(strip_tags($_POST['adviser_id']))
            ];

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

    public function getSectionById($section_id)
    {
        try {
            $getSectionResult = $this->sectionModel->getSectionById($section_id);
            if ($getSectionResult) {
                return ['success' => true, 'data' => $getSectionResult];
            } else {
                return ['success' => false, 'message' => "No section found with id of ($section_id)"];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}

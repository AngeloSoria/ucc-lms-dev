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
    public function addSection($sectionData)
    {
        try {
            $addResult = $this->sectionModel->addSection($sectionData);

            return $addResult['success'] == true ? $addResult : ['success' => false, 'message' => 'Something went wrong while adding Program.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Retrieves all sections using the model.
     */
    public function getAllSections()
    {
        try {
            return $this->sectionModel->getAllSections(); // Fetch all sections from the model
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateAcademicPeriod()
    {
        try {
            return $this->sectionModel->updateAcademicPeriod(); // Fetch all sections from the model
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
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

    public function updateSectionById($section_id, $data)
    {
        try {
            if (empty($data)) {
                throw new Exception("No data passed when trying to update section");
            };

            $updateResult = $this->sectionModel->updateSectionById($section_id, $data);
            if ($updateResult['success']) {
                return ['success' => true, 'message' => "Section has been updated."];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}

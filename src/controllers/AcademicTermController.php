<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Models']['AcademicTerm']);

class AcademicTermController
{
    private $db;
    private $academicTermModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->academicTermModel = new AcademicTerm($this->db);
    }

    public function addAcademicTerm($data)
    {
        try {
            // Deactivate the current active term before adding a new one
            $this->academicTermModel->deactivateCurrentActiveTerm();

            // Prepare the data for insertion
            $data['is_active'] = 1; // Set new term as active
            return $this->academicTermModel->addAcademicTerm($data);
        } catch (Exception $e) {
            // Log or handle the exception as needed
            return false; // Or return an error response
        }
    }

    public function getAllAcademicTerms()
    {
        return $this->academicTermModel->getAllAcademicTerm(); // Get academic terms from the model
    }

    // Method to get the current active term
    public function getCurrentActiveTerm()
    {
        return $this->academicTermModel->getCurrentActiveTerm(); // Get the current active term from the model
    }
}

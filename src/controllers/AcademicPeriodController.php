<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Models']['AcademicPeriod']);

class AcademicPeriodController
{
    private $academicPeriodModel;

    public function __construct($db)
    {
        $this->academicPeriodModel = new AcademicPeriod($db);
    }
    public function showCurrentDate()
    {
        // Get today's date
        $currentDate = date('Y-m-d');

        // Return the current date
        return ['success' => true, 'currentDate' => $currentDate];
    }
    // Check and update the active status of academic terms based on the current date
    public function checkAndUpdateActiveStatus()
    {
        try {
            // Get all academic terms
            $this->academicPeriodModel->checkActiveTerm();
            return ["success", "Academic terms' active status updated successfully."];
        } catch (Exception $e) {
            return ["error", "Failed to update active status: " . $e->getMessage()];
        }
    }



    // controllers/AcademicTermController.php
    public function addAcademicYearWithSemesters($academicData)
    {
        // Check if the academic year already exists
        if ($this->academicPeriodModel->isAcademicYearExists($academicData['academic_year_start'], $academicData['academic_year_end'])) {
            return ["error", "Academic year already exists."];
        } else {
            // Add academic year with semesters
            try {
                $response = $this->academicPeriodModel->addAcademicYearWithSemesters($academicData['academic_year_start'], $academicData['academic_year_end'], $academicData['first_semester'], $academicData['second_semester']);

                if ($response === true) {
                    return ["success", "Academic year with two semesters added successfully."];
                }
            } catch (Exception $e) {
                // Return error with message
                return ["error", "Failed to add academic year with semesters: " . $e->getMessage()];
            }
        }
    }

    // Get all academic terms
    public function getAllTerms()
    {
        try {
            $allTerms = $this->academicPeriodModel->getAllTerms();

            // If terms are found, return them
            if ($allTerms) {
                return $allTerms;
            }

            // In case no terms are found
            return ['error', 'No terms found.'];
        } catch (Exception $e) {
            // Return error with message
            return ["error", 'Failed to fetch all terms: ' . $e->getMessage()];
        }
    }

    // Get active academic terms only
    public function getActiveTerms()
    {
        try {
            $activeTerms = $this->academicPeriodModel->getActiveTerms();

            // If terms are found, return them
            if ($activeTerms) {
                return ['success' => true, 'data' => $activeTerms];
            }

            // In case no active terms are found
            return ['success' => false, 'message' => 'No active terms found.'];
        } catch (Exception $e) {
            // Return error with message
            return ['success' => false, 'error' => 'Failed to fetch active terms: ' . $e->getMessage()];
        }
    }
}

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

    // Show the current date in the format YYYY-MM-DD
    public function showCurrentDate()
    {
        try {
            $currentDate = date('Y-m-d'); // Get today's date
            return ['success' => true, 'currentDate' => $currentDate];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Failed to fetch current date: ' . $e->getMessage()];
        }
    }

    // Check and update the active status of academic terms based on the current date
    public function checkAndUpdateActiveStatus()
    {
        try {
            // Get all academic terms and check their status
            $this->academicPeriodModel->checkActiveTerm();
            return ["success" => true, "message" => "Academic terms' active status updated successfully."];
        } catch (Exception $e) {
            return ["success" => false, "error" => "Failed to update active status: " . $e->getMessage()];
        }
    }

    // Add an academic year with its semesters
    public function addAcademicYearWithSemesters($academicData)
    {
        try {
            // Check if the academic year already exists
            if ($this->academicPeriodModel->isAcademicYearExists($academicData['academic_year_start'], $academicData['academic_year_end'])) {
                return ["success" => false, "message" => "Academic year already exists."];
            }

            // Add the new academic year with semesters
            $response = $this->academicPeriodModel->addAcademicYearWithSemesters(
                $academicData['academic_year_start'],
                $academicData['academic_year_end'],
                $academicData['first_semester'],
                $academicData['second_semester']
            );

            if ($response === true) {
                return ["success" => true, "message" => "Academic year with two semesters added successfully."];
            } else {
                return ["success" => false, "message" => "Failed to add academic year with semesters."];
            }
        } catch (Exception $e) {
            return ["success" => false, "error" => "Failed to add academic year with semesters: " . $e->getMessage()];
        }
    }

    // Get all academic terms
    public function getAllTerms()
    {
        try {
            $allTerms = $this->academicPeriodModel->getAllTerms();

            // If terms are found, return them
            if ($allTerms) {
                return ['success' => true, 'data' => $allTerms];
            }

            // In case no terms are found
            return ['success' => false, 'message' => 'No terms found.'];
        } catch (Exception $e) {
            // Return error with message
            return ["success" => false, 'error' => 'Failed to fetch all terms: ' . $e->getMessage()];
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

    public function getAcademicPeriodById($period_id)
    {
        try {
            $result = $this->academicPeriodModel->getAcademicPeriodById($period_id);
            if ($result['success']) {
                return ['success' => true, 'data' => $result['data']];
            } else {
                return ['success' => false, 'message' => $result['message']];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}

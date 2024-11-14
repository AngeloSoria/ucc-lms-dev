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
    public function addAcademicYearWithSemesters()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'addTerm') {
            // Collect academic year data from POST request
            $academicYear = $_POST['start_year'] . '-' . $_POST['end_year'];
            $firstSemesterDates = [
                'start_date' => $_POST['first_semester_start'],
                'end_date' => $_POST['first_semester_end']
            ];
            $secondSemesterDates = [
                'start_date' => $_POST['second_semester_start'],
                'end_date' => $_POST['second_semester_end']
            ];

            // Check if the academic year already exists
            if ($this->academicPeriodModel->checkAcademicYear($academicYear)) {
                return ["error", "Academic year already exists."];
            } else {
                // Add academic year with semesters
                $response = $this->academicPeriodModel->addAcademicYearWithSemesters($academicYear, $firstSemesterDates, $secondSemesterDates);
                return ["success", "Academic term added successfully!"];
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
                return ['success' => true, 'data' => $allTerms];
            }

            // In case no terms are found
            return ['success' => false, 'message' => 'No terms found.'];
        } catch (Exception $e) {
            // Return error with message
            return ['success' => false, 'error' => 'Failed to fetch all terms: ' . $e->getMessage()];
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

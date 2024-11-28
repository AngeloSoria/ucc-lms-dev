<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Models']['Program']);
class ProgramController
{
    private $programModel;

    public function __construct()
    {
        $this->programModel = new Program();
    }

    // Simple validation before adding a program
    public function addProgram($programData)
    {
        try {
            // Read the program image file directly from $_FILES
            if (isset($_FILES['program_image']) && $_FILES['program_image']['error'] === UPLOAD_ERR_OK) {
                // Open the file and read its contents
                $programData['program_image'] = file_get_contents($_FILES['program_image']['tmp_name']);
            } else {
                $programData['program_image'] = null;  // Handle cases where there's no program image
            }
            $addResult = $this->programModel->addProgram($programData);

            return $addResult['success'] == true ? $addResult : ['success' => false, 'message' => 'Something went wrong while adding Program.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    public function getAllPrograms()
    {
        try {
            $programList = $this->programModel->getAllPrograms();  // Call the Model method to get programs
            if ($programList['success'] == false) {
                return ['success' => false, 'message' => "No programs retrieved."];
            } else {
                return empty($programList['data']) ? ['success' => false, 'message' => 'No programs retrieved'] : ['success' => true, 'data' => $programList['data']];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getAllProgramsByEducationalLevel($educational_level)
    {
        try {
            msgLog('Controller', $educational_level);
            $retrievedPrograms = $this->programModel->getAllProgramsByEducationalLevel($educational_level);

            if (!empty($retrievedPrograms['data'])) {
                return $retrievedPrograms;
            } else {
                return ['success' => false, 'message' => "No programs found with educational level of ($educational_level)."];
            }
        } catch (Exception $e) {
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    public function getProgramById($program_id)
    {
        try {
            $programList = $this->programModel->getProgramById($program_id);  // Call the Model method to get programs
            if ($programList['success'] == false) {
                return ['success' => false, 'message' => "No program retrieved with ($program_id)"];
            } else {
                return empty($programList['data']) ? ['success' => false, 'message' => "No program retrieved with ($program_id)"] : ['success' => true, 'data' => $programList['data']];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateProgram($program_data)
    {
        try {
            return $this->programModel->updateProgram($program_data);
        } catch (Exception $e) {
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    public function deleteProgramById($program_id)
    {
        try {
            $deleteResult = $this->programModel->deleteProgramById($program_id);
            return $deleteResult;
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}

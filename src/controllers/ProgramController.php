<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Models']['Program']);
class ProgramController
{
    private $db;
    private $programModel;

    public function __construct($db)
    {
        $this->programModel = new Program($db);
    }

    // Simple validation before adding a program
    public function addProgram($programData)
    {
        // Check if the program already exists
        if ($this->programModel->checkProgramExists($programData['program_code'], $programData['program_name'])) {
            return "Program already exists.";
        }

        // Read the program image file directly from $_FILES
        if (isset($_FILES['program_image']) && $_FILES['program_image']['error'] === UPLOAD_ERR_OK) {
            // Open the file and read its contents
            $programData['program_image'] = file_get_contents($_FILES['program_image']['tmp_name']);
        } else {
            $programData['program_image'] = null;  // Handle cases where there's no program image
        }

        // Add the program to the database
        $result = $this->programModel->addProgram($programData);

        return $result ? "Program added successfully!" : "Error adding program.";
    }
    public function showPrograms()
    {
        $programList = $this->programModel->getAllPrograms();  // Call the Model method to get programs
        return $programList;
    }


}

?>
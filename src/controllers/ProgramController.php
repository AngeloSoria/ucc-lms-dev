<?php
// ABSOLUTE ROOT_PATH
include_once $_SERVER['DOCUMENT_ROOT'] . "/ucc-lms-dev/src/config/rootpath.php";

// Adjust the path to the Program model based on your project structure
include_once ROOT_PATH . 'src/config/connection.php'; // Adjust the path accordingly
include_once ROOT_PATH . 'src/models/Program.php'; // Adjust the path accordingly

class ProgramController
{
    private $db;
    private $programModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->programModel = new Program($this->db);
    }

    public function addProgram()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and assign program data
            $programData = [
                'program_code' => htmlspecialchars(strip_tags($_POST['program_code'])),
                'program_name' => htmlspecialchars(strip_tags($_POST['program_name'])),
                'program_description' => htmlspecialchars(strip_tags($_POST['program_description'])),
                'educational_level' => htmlspecialchars(strip_tags($_POST['educational_level'])),
                'program_image' => null // Initialize program_image to null
            ];

            // Handle program image upload
            if (isset($_FILES['program_image']) && $_FILES['program_image']['error'] === UPLOAD_ERR_OK) {
                $programImage = $_FILES['program_image'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Allowed image types

                // Check file type
                if (in_array($programImage['type'], $allowedTypes)) {
                    $programData['program_image'] = file_get_contents($programImage['tmp_name']); // Store the image data
                } else {
                    echo "Invalid program image type.";
                    return; // Early exit
                }
            }

            // Call the model to add the program
            if ($this->programModel->addProgram($programData)) {
                // Success handling, e.g., redirect or display success message
                header('Location: ../program_admin.php?success=1');
            } else {
                // Error handling
                echo "Failed to add program.";
            }
        }
    }

    public function getAllPrograms()
    {
        return $this->programModel->getAllPrograms(); // Get programs from model
    }
}

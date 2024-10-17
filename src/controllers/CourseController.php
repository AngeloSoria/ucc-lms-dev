<?php
// Adjust the path to the Course model based on your project structure
include_once '../../../config/connection.php'; // Adjust the path accordingly
include_once '../../../models/Course.php'; // Adjust the path accordingly

class CourseController
{
    private $db;
    private $courseModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->courseModel = new Course($this->db);
    }

    public function addCourse()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and assign course data
            $courseData = [
                'course_code' => htmlspecialchars(strip_tags($_POST['course_code'])),
                'course_name' => htmlspecialchars(strip_tags($_POST['course_name'])),
                'course_description' => htmlspecialchars(strip_tags($_POST['course_description'])),
                'course_level' => htmlspecialchars(strip_tags($_POST['course_level'])),
                'course_image' => null // Initialize course_image to null
            ];

            // Handle course image upload
            if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === UPLOAD_ERR_OK) {
                $courseImage = $_FILES['course_image'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Allowed image types

                // Check file type
                if (in_array($courseImage['type'], $allowedTypes)) {
                    $courseData['course_image'] = file_get_contents($courseImage['tmp_name']); // Store the image data
                } else {
                    echo "Invalid course image type.";
                    return; // Early exit
                }
            }

            // Call the model to add the course
            if ($this->courseModel->addCourse($courseData)) {
                // Success handling, e.g., redirect or display success message
                echo "succes to add course";
            } else {
                // Error handling
                echo "Failed to add course.";
            }
        }
    }

    public function getAllCourses()
    {
        return $this->courseModel->getAllCourses(); // Get courses from model
    }
}

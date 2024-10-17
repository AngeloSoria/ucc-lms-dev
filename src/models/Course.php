<?php
class Course
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function addCourse($courseData)
    {
        // Prepare the SQL statement
        $stmt = $this->db->prepare("INSERT INTO courses (course_code, course_name, course_description, course_level, course_image) VALUES (:course_code, :course_name, :course_description, :course_level, :course_image)");

        // Bind the parameters
        $stmt->bindParam(':course_code', $courseData['course_code']);
        $stmt->bindParam(':course_name', $courseData['course_name']);
        $stmt->bindParam(':course_description', $courseData['course_description']);
        $stmt->bindParam(':course_level', $courseData['course_level']);
        $stmt->bindParam(':course_image', $courseData['course_image'], PDO::PARAM_LOB); // Use PDO::PARAM_LOB for large objects

        // Execute the query and return success or failure
        return $stmt->execute();
    }

    public function getAllCourses()
    {
        $query = "SELECT course_code, course_name, course_description, course_level, course_image FROM courses";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return an array of courses
    }
}

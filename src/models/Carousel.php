<?php
class Carousel
{
    private $conn;
    private $table_name = "carousel"; // Table name for the carousel

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function addCarouselItem($data)
    {
        $query = "INSERT INTO " . $this->table_name . " (title, image, view_type) VALUES (:title, :image, :view_type)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':image', $data['image'], PDO::PARAM_LOB); // Use PDO::PARAM_LOB for large objects
        $stmt->bindParam(':view_type', $data['view_type']);

        return $stmt->execute(); // Return true if successful, false otherwise
    }

    public function getAllCarouselItems()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as associative array
    }
}

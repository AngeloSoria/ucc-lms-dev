<?php

class Carousel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Method to add a new carousel item
    public function addCarouselItem($data)
    {
        $query = "INSERT INTO carousel (title, view_type, image, is_selected) VALUES (:title, :view_type, :image, :is_selected)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':view_type', $data['view_type']);
        $stmt->bindParam(':image', $data['image'], PDO::PARAM_LOB);
        $stmt->bindParam(':is_selected', $data['is_selected']);

        return $stmt->execute();
    }

    // Method to get the count of selected items for a given view_type
    public function getSelectedItemsCount($viewType)
    {
        $query = "SELECT COUNT(*) FROM carousel WHERE view_type = :view_type AND is_selected = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':view_type', $viewType);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    // Method to deselect the oldest selected item
    public function deselectOldestItem($viewType)
    {
        $query = "UPDATE carousel SET is_selected = 0 WHERE view_type = :view_type AND is_selected = 1 ORDER BY created_at ASC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':view_type', $viewType);

        return $stmt->execute();
    }

    // Method to fetch all carousel items (for display purposes)
    public function getAllCarouselItems()
    {
        $query = "SELECT * FROM carousel ORDER BY created_at DESC";
        $stmt = $this->conn->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

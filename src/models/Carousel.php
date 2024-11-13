<?php

class Carousel
{
    private $conn;
    private $table_name = 'carousel';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Method to add a new carousel item
    public function addCarouselItem($data)
    {
        $query = "INSERT INTO " . $this->table_name . " (title, image_path, view_type, is_selected) VALUES (:title, :image_path, :view_type, :is_selected)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':image_path', $data['image_path']);
        $stmt->bindParam(':view_type', $data['view_type']);
        $stmt->bindParam(':is_selected', $data['is_selected']);

        return $stmt->execute();
    }

    // Method to update an existing carousel item by ID
    public function updateCarouselItem($id, $data)
    {
        $query = "UPDATE " . $this->table_name . " SET title = :title, image_path = :image_path, view_type = :view_type, is_selected = :is_selected WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':image_path', $data['image_path']);
        $stmt->bindParam(':view_type', $data['view_type']);
        $stmt->bindParam(':is_selected', $data['is_selected']);

        return $stmt->execute();
    }

    // Method to delete a carousel item by ID
    public function deleteCarouselItem($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Method to get the count of selected items for a given view_type
    public function getSelectedItemsCount($viewType)
    {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE view_type = :view_type AND is_selected = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':view_type', $viewType);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    // Method to deselect the oldest selected item
    public function deselectOldestItem($viewType)
    {
        $query = "UPDATE " . $this->table_name . " SET is_selected = 0 WHERE view_type = :view_type AND is_selected = 1 ORDER BY created_at ASC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':view_type', $viewType);

        return $stmt->execute();
    }

    // Method to fetch all carousel items (for display purposes)
    public function getAllCarouselItems()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

<?php

class Announcements
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Add a new announcement
    public function addAnnouncement($announcementData)
    {
        try {
            $query = "INSERT INTO announcements (announcer_id, subject_section_id, title, message, is_global) 
                      VALUES (:announcer_id, :subject_section_id, :title, :message, :is_global)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':announcer_id', $announcementData['announcer_id'], PDO::PARAM_INT);
            $stmt->bindParam(':subject_section_id', $announcementData['subject_section_id'], PDO::PARAM_INT);
            $stmt->bindParam(':title', $announcementData['title']);
            $stmt->bindParam(':message', $announcementData['message']);
            $stmt->bindParam(':is_global', $announcementData['is_global'], PDO::PARAM_INT);

            $stmt->execute(); // Execute statement

            return ["success" => true];
        } catch (PDOException $e) {
            return ['success' => false, "message" => "Error: " . $e->getMessage()];
        }
    }

    // Get all announcements for a specific subject_section
    public function getAnnouncementsBySubjectSection($subject_section_id)
    {
        try {
            $query = "SELECT
                        a.*,
                        CONCAT(
                            IFNULL(u.last_name, ''), ', ',
                            IFNULL(u.first_name, ''), ' ',
                            IFNULL(u.middle_name, '')
                        ) AS announcer_name
                        FROM announcements as a
                        JOIN users as u
                        ON u.user_id = a.announcer_id
                        WHERE subject_section_id = :subject_section_id 
                        ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':subject_section_id', $subject_section_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array

            return $result ? $result : [];
        } catch (PDOException $e) {
            throw new PDOException("Failed to get announcements: " . $e->getMessage());
        }
    }

    // Update an existing announcement
    public function updateAnnouncement($announcementData)
    {
        try {
            $query = "UPDATE announcements 
                      SET title = :title, message = :message, updated_at = CURRENT_TIMESTAMP
                      WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $announcementData['id'], PDO::PARAM_INT);
            $stmt->bindParam(':title', $announcementData['title']);
            $stmt->bindParam(':message', $announcementData['message']);

            $stmt->execute(); // Execute statement

            return ["success" => true];
        } catch (PDOException $e) {
            return ['success' => false, "message" => "Error: " . $e->getMessage()];
        }
    }

    // Delete an announcement
    public function deleteAnnouncement($id)
    {
        try {
            $query = "DELETE FROM announcements WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute(); // Execute statement

            return ["success" => true];
        } catch (PDOException $e) {
            return ['success' => false, "message" => "Error: " . $e->getMessage()];
        }
    }

    // In Announcements model
    public function getGlobalAnnouncements()
    {
        try {
            $query = "SELECT
                        a.*,
                        CONCAT(
                            IFNULL(u.last_name, ''), ', ',
                            IFNULL(u.first_name, ''), ' ',
                            IFNULL(u.middle_name, '')
                        ) AS announcer_name
                    FROM announcements AS a
                    JOIN users AS u
                    ON u.user_id = a.announcer_id
                    WHERE is_global = 1
                    ORDER BY created_at DESC;
                    ";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result ? $result : [];
        } catch (PDOException $e) {
            throw new PDOException("Failed to get global announcements: " . $e->getMessage());
        }
    }
}

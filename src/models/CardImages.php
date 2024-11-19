<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);

class CardImages
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // ADD
    //GET
    public function getImageByRole($role)
    {
        try {
            $query = "SELECT image FROM role_images WHERE role = :role";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':role', $role);
            $stmt->execute();

            $retreivedCardInfo = $stmt->fetch(PDO::FETCH_ASSOC);

            return ['success' => true, 'data' => $retreivedCardInfo];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    // UPDATE
    public function updateImageByRole($role)
    {
        try {
            $query = "SELECT image FROM role_images WHERE role = :role";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':role', $role);
            $stmt->execute();

            $retreivedCardInfo = $stmt->fetch(PDO::FETCH_ASSOC);

            return ['success' => true, 'data' => $retreivedCardInfo];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // DELETE

}

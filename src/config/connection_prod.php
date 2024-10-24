<?php
class Database
{
    private $pdo;

    public function __construct()
    {
        $host = '127.0.0.1';
        $db   = 'u661545712_ucc_db';
        $user = 'u661545712_root';
        $pass = '!7o/X3@G';
        $port = 3306;

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
        try {
            $this->pdo = new PDO($dsn, $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Return the error as JSON
            echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
            exit();
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}

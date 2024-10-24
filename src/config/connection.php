<?php

require_once(__DIR__ . "/PathsHandler.php");
require_once(VENDOR_AUTO_LOAD);

use Dotenv\Dotenv;

class Database
{
    private $pdo;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(BASE_PATH);
        $dotenv->load();

        $host = getenv('DB_HOST') or die('Missing DB_HOST environment variable') ;
        $db   = getenv('DB_NAME') or die('Missing DB_NAME environment variable');
        $user = getenv('DB_USER') or die('Missing DB_USER environment variable') ;
        $pass = getenv('DB_PASS') or die('Missing DB_PASSWORD environment variable');
        $port = getenv('DB_PORT') or die('Missing DB_PORT environment variable');

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


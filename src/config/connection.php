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

        # Check if the connection is in localhost or production environment.
        switch ($_ENV['APP_ENV']) {
            case "local":
                $host = "localhost";
                $db = "ucc_db_dev";
                $user = "root";
                $pass = "";
                $port = 3306;
                break;
            case "prod":
                $host = $_ENV['DB_HOST'] or die('Missing DB_HOST environment variable');
                $db = $_ENV['DB_NAME'] or die('Missing DB_NAME environment variable');
                $user = $_ENV['DB_USER'] or die('Missing DB_USER environment variable');
                $pass = $_ENV['DB_PASS'] or die('Missing DB_PASSWORD environment variable');
                $port = $_ENV['DB_PORT'] or die('Missing DB_PORT environment variable');
                break;
            default:
                echo 'Unknown APP_ENV value. Please set it to either "local" or "prod".';
                break;
        }

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


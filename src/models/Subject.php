<?php
class User
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function addSubject($userData)
    {

    }

    // You can add more methods for updating, deleting, and retrieving subject as needed.
}

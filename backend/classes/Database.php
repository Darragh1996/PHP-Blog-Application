<?php
class Database
{
    protected $conn;

    public function __construct()
    {
        // establish db connection
        $this->conn = new PDO('mysql:host=localhost;dbname=blog_app', 'root', '');
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getConnection()
    {
        return $this->conn;
    }
}

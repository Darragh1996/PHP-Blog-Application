<?php
require_once 'Database.php';

class Blog extends Database
{
    public $id;
    public $user_id;
    public $date;
    public $text;

    public function __construct()
    {
        parent::__construct();
    }

    // get all blogs
    public function getAll()
    {
        try {
            $query = "SELECT * FROM blogs";
            $stmt = $this->conn->query($query);

            if ($stmt->execute()) {
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $rows; // Return all fetched rows
            }
            return false;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // get single blog by user id
    public function getByID()
    {
        try {
            $query = "SELECT * FROM blogs WHERE id = :id";
            $stmt = $this->conn->prepare($query);

            // prevent SQL injection -> input values treated as data
            // and not executable SQL
            $stmt->bindParam(":id", $this->id);

            if ($stmt->execute()) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return $row; // Return the fetched row
            }
            return false;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // add a new blog 
    public function add()
    {
        try {
            $query = "INSERT INTO blogs (user_id, text) VALUES (:user_id, :text)";
            $stmt = $this->conn->prepare($query);

            // prevent SQL injection -> input values treated as data
            // and not executable SQL
            $stmt->bindParam(":user_id", $this->user_id);
            $stmt->bindParam(":text", $this->text);


            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // update an existing blog 
    public function update()
    {
        // echo $this->id;
        try {
            $query = "UPDATE blogs SET text = :text WHERE id = :id";
            $stmt = $this->conn->prepare($query);

            // prevent SQL injection -> input values treated as data
            // and not executable SQL
            $stmt->bindParam(":text", $this->text);
            $stmt->bindParam(":id", $this->id);


            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // update an existing blog 
    public function delete()
    {
        try {
            $query = "DELETE FROM blogs WHERE id = :id";
            $stmt = $this->conn->prepare($query);

            // prevent SQL injection -> input values treated as data
            // and not executable SQL
            $stmt->bindParam(":id", $this->id);


            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}

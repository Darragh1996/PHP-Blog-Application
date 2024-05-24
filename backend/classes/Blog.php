<?php
require_once 'Database.php';

class Blog extends Database
{
    public $id;
    public $user_id;
    public $date;
    public $edit_date;
    public $title;
    public $text;

    public function __construct()
    {
        parent::__construct();
    }

    // get all blogs
    public function getAll()
    {
        try {
            $query = "SELECT blogs.id, blogs.user_id, blogs.date, blogs.edit_date, blogs.title, users.username,
            CASE WHEN blogs.user_id = :user_id THEN true ELSE false END AS is_owner
            FROM blogs INNER JOIN users ON blogs.user_id=users.id";
            $stmt = $this->conn->prepare($query);

            // prevent SQL injection -> input values treated as data
            // and not executable SQL
            $stmt->bindParam(":user_id", $this->user_id);

            if ($stmt->execute()) {
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $rows; // Return all fetched rows
            }
            return false;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // get single blog by blog id
    public function getByID()
    {
        // echo $this->id;
        try {
            $query = "SELECT blogs.id, blogs.user_id, blogs.date, blogs.edit_date, blogs.title, blogs.text, users.username
            FROM blogs INNER JOIN users ON blogs.user_id=users.id
            WHERE blogs.id = :id";
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
            $query = "INSERT INTO blogs (user_id, title, text) VALUES (:user_id, :title, :text)";
            $stmt = $this->conn->prepare($query);

            // prevent SQL injection -> input values treated as data
            // and not executable SQL
            $stmt->bindParam(":user_id", $this->user_id);
            $stmt->bindParam(":title", $this->title);
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
            $query = "UPDATE blogs SET edit_date = :edit_date, title = :title, text = :text WHERE id = :id";
            $stmt = $this->conn->prepare($query);

            // prevent SQL injection -> input values treated as data
            // and not executable SQL
            $stmt->bindParam(":edit_date", $this->edit_date);
            $stmt->bindParam(":title", $this->title);
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

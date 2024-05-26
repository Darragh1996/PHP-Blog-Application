<?php
require_once 'Database.php';

class Comment extends Database
{
    public $id;
    public $user_id;
    public $blog_id;
    public $date;
    public $text;

    public function __construct()
    {
        parent::__construct();
    }

    // get all comments for a specific blog
    public function getAllByBlogID()
    {
        try {
            $query = "SELECT comments.id, comments.text, comments.user_id, comments.blog_id, comments.date, users.username
            FROM comments 
            INNER JOIN users ON comments.user_id = users.id 
            WHERE comments.blog_id = :blog_id";
            $stmt = $this->conn->prepare($query);

            // prevent SQL injection -> input values treated as data
            // and not executable SQL
            $stmt->bindParam(":blog_id", $this->blog_id);

            if ($stmt->execute()) {
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $rows; // Return all fetched rows
            }
            return false;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // add a new comment 
    public function add()
    {
        try {
            $query = "INSERT INTO comments (user_id, blog_id, text) VALUES (:user_id, :blog_id, :text)";
            $stmt = $this->conn->prepare($query);

            // prevent SQL injection -> input values treated as data
            // and not executable SQL
            $stmt->bindParam(":user_id", $this->user_id);
            $stmt->bindParam(":blog_id", $this->blog_id);
            $stmt->bindParam(":text", $this->text);


            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // update an existing comment 
    public function delete()
    {
        try {
            $query = "DELETE FROM comments WHERE id = :id";
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

<?php
require_once 'Database.php';

class User extends Database
{
    public $id;
    public $username;
    public $email;
    public $password;

    public function __construct()
    {
        parent::__construct();
    }

    // get single user
    public function get()
    {
        try {
            $query = "SELECT * FROM users WHERE id = :id";
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

    public function add()
    {
        try {
            $query = "INSERT INTO users (username, email, hashed_password) VALUES (:username, :email, :password)";
            $stmt = $this->conn->prepare($query);

            // hash the password before inserting into database
            // this should probably be done on the frontend so that the
            // plain text password isn't exposed in transit
            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);

            // echo "Hashed password: " . $this->password . "\n";
            // echo "Length: " . strlen($this->password);

            // prevent SQL injection -> input values treated as data
            // and not executable SQL
            $stmt->bindParam(":username", $this->username);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":password", $this->password);


            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}

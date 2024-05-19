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

    // get all users
    public function getAll()
    {
        try {
            $query = "SELECT * FROM users";
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

    // get single user by user id
    public function getByID()
    {
        try {
            $query = "SELECT * FROM users WHERE id = :id";
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

    // add a new user / register
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

    public function login($username, $password)
    {
        try {
            $query = "SELECT * FROM users WHERE username = :username";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":username", $username);

            if ($stmt->execute()) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['hashed_password'])) {
                    // Password is correct
                    return [
                        'status' => 'success',
                        'message' => 'Login successful',
                        'user' => $user
                    ];
                } else {
                    // Incorrect password or user not found
                    return [
                        'status' => 'error',
                        'message' => 'Invalid username or password'
                    ];
                }
            }
            return false;
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}

<?php

require_once('./database/Database.php');

class Authentication {
    private $db;
    private $username;
    private $password;
    private $role;
    private $user_id;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function login()
    {
        $this->username = $this->sanitize($_POST['username']);
        $this->password = $this->sanitize($_POST['password']);

        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($this->password, $user['password'])) {
                $this->user_id = $user['user_id'];
                $this->role = $user['role_id'];
                // return ["status" => "success", "message" => "Login successful", "user" => $user];
                echo "<script>console.log('Login successful')</script>";
                return true;
            } else {
                // return ["status" => "error", "message" => "Invalid credentials"];
                echo "<script>console.log('Invalid credentials')</script>";
                return false;
            }
        } else {
            // return ["status" => "error", "message" => "User not found"];
            echo "<script>console.log('No account associated with that username')</script>";
        }
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getUserType()
    {
        return $this->role;
    }

    private function sanitize($param)
    {
        $param = trim($param);
        $param = stripslashes($param);
        $param = htmlspecialchars($param);
        return $param;
    }
}
?>

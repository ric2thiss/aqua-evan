<?php

class User {
    private PDO $dbconn;

    public function __construct(PDO $dbconnection) {
        $this->dbconn = $dbconnection;
    }

    // Example function to fetch user by ID
    public function getUserById(int $user_id): ?array {
        try {
            $stmt = $this->dbconn->prepare("SELECT * FROM users INNER JOIN role ON role.role_id = users.role_id WHERE user_id = :user_id");
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch() ?: null;
        } catch (PDOException $e) {
            error_log("User fetch error: " . $e->getMessage());
            return null;
        }
    }
}
?>

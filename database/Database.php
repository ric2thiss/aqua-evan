<?php

class Database {
    private string $host = "localhost";
    private string $db_name = "aqua-evan";
    private string $username = "root";
    private string $password = "";
    private ?PDO $conn = null;

    public function connect(): ?PDO {
        if ($this->conn === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
                $this->conn = new PDO($dsn, $this->username, $this->password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                error_log("Database connection error: " . $e->getMessage()); // Log error instead of exposing it
                throw new Exception("Database connection failed."); // Generic error message
            }
        }
        return $this->conn;
    }
}
?>

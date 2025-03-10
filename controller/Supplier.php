<?php

class Supplier
{
    private PDO $dbconn;

    public function __construct(PDO $dbconnection)
    {
        $this->dbconn = $dbconnection;
    }

    public function all()
    {
        try {
            $stmt = $this->dbconn->prepare("SELECT * FROM suppliers");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            echo "Error: " . $error->getMessage();
        }
    }

    public function insert()
    {
        $supplier_name = $this->sanitize($_POST["supplier_name"]);

        if(empty($supplier_name))
        {
            return false;
        }
        try {
            $sql = "INSERT INTO suppliers(supplier_name) VALUES (:supplier_name)";
            $stmt = $this->dbconn->prepare($sql);
            $stmt->bindParam(':supplier_name', $supplier_name);
            $stmt->execute();
            return true;
        } catch (PDOException $error) {
            echo "Error: " . $error->getMessage();
            return false;
        }
    }

    // Sanitization
    private function sanitize($param)
    {
        $param = trim($param);
        $param = stripslashes($param);
        $param = htmlspecialchars($param);
        return $param;
    }
}

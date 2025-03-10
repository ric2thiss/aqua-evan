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
        try{
            $stmt = $this->dbconn->prepare("SELECT * FROM suppliers");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $error){
            echo "Error: " . $error->getMessage();
        }
    }
}
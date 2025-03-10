<?php
class Product
{
    private PDO $dbconn;

    public function __construct(PDO $dbconnection)
    {
        $this->dbconn = $dbconnection;
    }

    public function all(): ?array
    {
        return [];
    }

    // Example function to fetch user by ID
    // public function getUserById(int $user_id): ?array
    // {
    //     try {
    //         $stmt = $this->dbconn->prepare("SELECT * FROM users INNER JOIN role ON role.role_id = users.role_id WHERE user_id = :user_id");
    //         $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    //         $stmt->execute();
    //         return $stmt->fetch() ?: null;
    //     } catch (PDOException $e) {
    //         error_log("User fetch error: " . $e->getMessage());
    //         return null;
    //     }
    // }

    public function insert_supplier($supplier_name)
    {
        $supplier = $this->sanitize($supplier_name);

        try {
            $stmt = $this->dbconn->prepare("INSERT INTO suppliers (supplier_name) VALUES (:supplier_name)");
            $stmt->bindParam(":supplier_name", $supplier, PDO::PARAM_STR);
            $stmt->execute();
            return $this->dbconn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error inserting supplier: " . $e->getMessage());
            return false;
        }
    }

    public function insert_product($data = [])
    {
        $product_name = $this->sanitize($data["product_name"]);
        $product_cost = $this->sanitize($data["product_cost"]);
        $product_quantity = $this->sanitize($data["product_quantity"]);
        $product_total_cost = $product_cost * $product_quantity;
        $supplier_name = $this->sanitize($data["supplier_name"]);
        $date = $this->sanitize($data["date"]);
        $user_id = $this->sanitize($data["user_id"]);
        $activity = $this->sanitize($data["activity"]);

        try {
            $create_supplier = $this->insert_supplier($supplier_name);
            $create_activity = $this->insert_activity($activity, $user_id);

            if ($create_supplier && $create_activity) {
                $sql = "INSERT INTO products (product_name, product_cost, product_quantity, product_total_cost, supplier_id, date, user_id, activity_id) 
                        VALUES (:product_name, :product_cost, :product_quantity, :product_total_cost, :supplier_id, :date, :user_id, :activity_id)";
                $stmt = $this->dbconn->prepare($sql);
                $stmt->bindParam(":product_name", $product_name);
                $stmt->bindParam(":product_cost", $product_cost);
                $stmt->bindParam(":product_quantity", $product_quantity);
                $stmt->bindParam(":product_total_cost", $product_total_cost);
                $stmt->bindParam(":supplier_id", $create_supplier);
                $stmt->bindParam(":date", $date);
                $stmt->bindParam(":user_id", $user_id);
                $stmt->bindParam(":activity_id", $create_activity);

                if ($stmt->execute()) {
                    return true;
                }
            }
            return false;
        } catch (PDOException $error) {
            error_log("Error inserting product: " . $error->getMessage());
            return false;
        }
    }

    // Activities
    public function insert_activity($activity, $user_id)
    {
        $activity_name = $this->sanitize($activity);
        $userId = $this->sanitize($user_id);

        try {
            $stmt = $this->dbconn->prepare("INSERT INTO activity (activity_name, user_id) VALUES (:activity_name, :user_id)");
            $stmt->bindParam(":activity_name", $activity_name, PDO::PARAM_STR);
            $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $this->dbconn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error inserting activity: " . $e->getMessage());
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

<?php

class Orders
{
    private PDO $dbconn;

    public function __construct(PDO $dbconnection)
    {
        $this->dbconn = $dbconnection;
    }

    public function get_order_type()
    {
        try {
            $stmt = $this->dbconn->prepare("SELECT ot.* FROM orders RIGHT JOIN order_types ot ON orders.order_type_id = ot.order_type_id");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching order types: " . $e->getMessage());
            return null;
        }
    }

    public function insert_order_details($data = [])
    {
        try {
            // Extract and sanitize data
            $order_unit_price = (float) $data["order_unit_price"];
            $order_total_quantity = (int) $data["order_total_quantity"];
            $order_total_cost = (float) $data["order_total_price"];
            $order_addons_id = isset($data["order_addons_id"]) ? (int) $data["order_addons_id"] : null;
            $payment_method = $this->sanitize($data["payment_method"]);
            $received_amount = (float) $data["received_amount"];
            $change_amount = (float) $data["change_amount"];

            $sql = "INSERT INTO order_details 
                    (order_unit_price, order_total_quantity, order_total_cost, order_addons_id, payment_method, received_amount, change_amount) 
                    VALUES (:order_unit_price, :order_total_quantity, :order_total_cost, :order_addons_id, :payment_method, :received_amount, :change_amount)";

            $stmt = $this->dbconn->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':order_unit_price', $order_unit_price);
            $stmt->bindParam(':order_total_quantity', $order_total_quantity);
            $stmt->bindParam(':order_total_cost', $order_total_cost);
            $stmt->bindParam(':order_addons_id', $order_addons_id, PDO::PARAM_INT);
            $stmt->bindParam(':payment_method', $payment_method);
            $stmt->bindParam(':received_amount', $received_amount);
            $stmt->bindParam(':change_amount', $change_amount);

            $stmt->execute();
            return $this->dbconn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error inserting order details: " . $e->getMessage());
            return null;
        }
    }

    public function insert_category()
    {
        return;
    }

    public function insert_order($data = [])
    {
        try{
            $employee_id = $data["user_id"];
            $order_type_id = $data["order_type_id"];
            $customer_id = $data["customer_id"];
            $order_details_id = $this->insert_order_details();
            $order_category_id = $this>insert_category();
        } catch (PDOException $e) {
            error_log("Error inserting order details: " . $e->getMessage());
            return null;
        }
    }

    // Sanitization for strings
    private function sanitize($param)
    {
        return htmlspecialchars(trim($param), ENT_QUOTES, 'UTF-8');
    }
}

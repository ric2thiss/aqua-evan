
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
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }


    public function insert_order()
    {
        try {
            $stmt = $this->dbconn->prepare("INSERT INTO orders (order_type_id,
            order_date, order_status_id, customer_id, total) VALUES (:order_type_id, :
            order_date, :order_status_id, :customer_id, :total)");
            $stmt->bindParam(':order_type_id', $this->order_type_id);
            $stmt->bindParam(':order_date', $this->order_date);
            $stmt->bindParam(':order_status_id', $this->order_status_id);
            $stmt->bindParam(':customer_id', $this->customer_id);
            $stmt->bindParam(':total', $this->total);
            $stmt->execute();
            return $this->dbconn->lastInsertId();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function insert_customer()
    {
        try {
            $stmt = $this->dbconn->prepare("INSERT INTO customers (first_name, last
            _name, email, phone_number) VALUES (:first_name, :last_name, :email
            , :phone_number)");
            $stmt->bindParam(':first_name', $this->first_name);
            $stmt->bindParam(':last_name', $this->last_name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':phone_number', $this->phone_number);
            $stmt->execute();
            return $this->dbconn->lastInsertId();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function insert_order_details()
    {
        try {
            $stmt = $this->dbconn->prepare("INSERT INTO order_details (order_id,
            product_id, quantity) VALUES (:order_id, :product_id, :quantity)");
            $stmt->bindParam(':order_id', $this->order_id);
            $stmt->bindParam(':product_id', $this->product_id);
            $stmt->bindParam(':quantity', $this->quantity);
            $stmt->execute();
            return $this->dbconn->lastInsertId();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function insert_order_category()
    {
        try {
            $stmt = $this->dbconn->prepare("INSERT INTO order_category (order_id,
            category_id) VALUES (:order_id, :category_id)");
            $stmt->bindParam(':order_id', $this->order_id);
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->execute();
            return $this->dbconn->lastInsertId();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }



    public function insert_activity()
    {
        // Insert
        try {
            $stmt = $this->dbconn->prepare("INSERT INTO activity (activity_id, activity
            , activity_date, activity_time, activity_location, activity_status, user_id)
            VALUES (:activity_id, :activity, :activity_date, :activity_time, :activity_location
            , :activity_status, :user_id)");
            $stmt->bindParam(':activity_id', $this->activity_id);
            $stmt->bindParam(':activity', $this->activity);
            $stmt->bindParam(':activity_date', $this->activity_date);
            $stmt->bindParam(':activity_time', $this->activity_time);
            $stmt->bindParam(':activity_location', $this->activity_location);
            $stmt->bindParam(':activity_status', $this->activity_status);
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->execute();
            return $this->dbconn->lastInsertId();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
}

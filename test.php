<?php
require_once('./database/Database.php');

class Seeding extends Database
{
    public function create_account($firstname, $lastname, $contact, $address, $username, $password, $role_id)
    {
        $conn = $this->connect();
        $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO `users`(`firstname`, `lastname`, `contact`, `address`, `username`, `password`, `role_id`, `created_at`) 
        VALUES (:firstname, :lastname, :contact, :address, :username, :password, :role_id, NOW())";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedpassword);
        $stmt->bindParam(':role_id', $role_id);
        
        return $stmt->execute();
    }
}

$create_user = new Seeding();

if ($create_user->create_account("Ric Charles", "Paquibot", "09063804889", "Purok-2A, Ampayon", "admin", "admin1", 1)) {
    echo "User created";
} else {
    echo "Error creating user";
}

<?php
class User
{
    private $conn;
    private $table_name = "tb_users";

    public $user_id;
    public $user_name;
    public $user_phone;
    public $user_room;
    public $amd_id;
    public $acc_id;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function userData($acc_id)
    {
        $id = $acc_id;
        $query = "SELECT * FROM {$this->table_name}  WHERE acc_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user;
        } else {
            return false;
        }
    }

    public function insertUsers()
    {
        $user_status = '1';
        $query = "INSERT INTO {$this->table_name}
        (user_name, user_phone, user_room, amd_id, acc_id, user_status) 
        VALUES (:user_name, :user_phone, :user_room, :amd_id, :acc_id, :user_status)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_name', $this->user_name);
        $stmt->bindParam(':user_phone', $this->user_phone);
        $stmt->bindParam(':user_room', $this->user_room);
        $stmt->bindParam(':amd_id', $this->amd_id);
        $stmt->bindParam(':acc_id', $this->acc_id);
        $stmt->bindParam(':user_status', $user_status);

        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            // Handle the error
            echo "Error: " . $e->getMessage();
        }

        return false;
    }

    public function getUserById($id)
    {
        try {
            $query = "SELECT u.*,a.* FROM {$this->table_name} AS u
                      JOIN tb_accommodations AS a ON u.amd_id = a.amd_id
                      WHERE acc_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // จัดการกับข้อผิดพลาด เช่น บันทึกข้อผิดพลาดลงไฟล์ล็อก หรือแสดงข้อความข้อผิดพลาด
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function updateAccountUsers($user_id)
    {
        // ตรวจสอบค่าว่างเปล่า
        if (empty($this->user_name) || empty($this->user_phone) || empty($this->user_room) || empty($this->amd_id)) {
            throw new Exception("All fields are required.");
        }

        $query = "UPDATE {$this->table_name} SET user_name = :user_name, user_phone = :user_phone,
             user_room = :user_room,amd_id = :amd_id
       WHERE user_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_name', $this->user_name);
        $stmt->bindParam(':user_phone', $this->user_phone);
        $stmt->bindParam(':user_room', $this->user_room);
        $stmt->bindParam(':amd_id', $this->amd_id);

        try {
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Failed to update user account : " . $e->getMessage());
        }
    }


    public function updateProfileUser($user_id)
    {
        // ตรวจสอบค่าว่างเปล่า
        if (empty($this->user_name) || empty($this->user_phone) || empty($this->user_room) ) {
            throw new Exception("All fields are required.");
        }

        $query = "UPDATE {$this->table_name} SET user_name = :user_name, user_phone = :user_phone,
                  user_room = :user_room
                 WHERE user_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_name', $this->user_name);
        $stmt->bindParam(':user_phone', $this->user_phone);
        $stmt->bindParam(':user_room', $this->user_room);

        try {
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Failed to update user account : " . $e->getMessage());
        }
    }
}

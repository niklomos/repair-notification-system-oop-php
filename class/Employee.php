<?php
class Employee
{
    private $conn;
    private $table_name = "tb_employees";

    public $emp_id;
    public $emp_name;
    public $emp_phone;
    public $emp_address;
    public $acc_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function insertEmployees() {
        $emp_status = '1';
        $query = "INSERT INTO {$this->table_name}
        (emp_name, emp_phone, emp_address, emp_status,acc_id) 
        VALUES (:emp_name, :emp_phone, :emp_address, :emp_status, :acc_id)";
        
        $stmt = $this->conn->prepare($query);        
        $stmt->bindParam(':emp_name', $this->emp_name);
        $stmt->bindParam(':emp_phone', $this->emp_phone);
        $stmt->bindParam(':emp_address', $this->emp_address);
        $stmt->bindParam(':emp_status', $emp_status);
        $stmt->bindParam(':acc_id', $this->acc_id);

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

    public function employeeData($acc_id)
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


    public function getEmployeeById($id)
    {
        try {
            $query = "SELECT * FROM {$this->table_name} WHERE acc_id = :id";
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
    
  
    public function updateProfileEmployee($emp_id)
    {
        // ตรวจสอบค่าว่างเปล่า
        if (empty($this->emp_name) || empty($this->emp_phone)  || empty($this->emp_address)) {
            throw new Exception("All fields are required.");
        }

        $query = "UPDATE {$this->table_name} SET emp_name = :emp_name, emp_phone = :emp_phone,emp_address = :emp_address
                 WHERE emp_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $emp_id, PDO::PARAM_INT);
        $stmt->bindParam(':emp_name', $this->emp_name);
        $stmt->bindParam(':emp_phone', $this->emp_phone);
        $stmt->bindParam(':emp_address', $this->emp_address);

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


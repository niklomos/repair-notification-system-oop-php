<?php 
session_start(); // เริ่มต้น session

class Account{

    private $conn;
    private $table_name = "tb_accounts";
    public $username;
    public $password;
    // employees
    public $emp_name;
    public $emp_phone;
    public $emp_address;
    

    public function __construct($db) {
        $this->conn = $db;
    }

    public function checkAccount($username, $password){
        $query = "SELECT * FROM {$this->table_name} WHERE username = :username AND password = :password AND acc_status = '1'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        
        try {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                 // ดึงข้อมูลแถวแรก (เพราะ username และ password ควรจะเป็นค่าที่ไม่ซ้ำกัน)
                 $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                 // เก็บ user_id ใน session
                 $_SESSION['acc_id'] = $row['acc_id']; 
                 $_SESSION['acc_permission'] = $row['acc_permission']; // เปลี่ยน 'id' เป็นชื่อคอลัมน์ที่เก็บ user ID ในฐานข้อมูลของคุณ
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Failed to check account: " . $e->getMessage());
        }
    }

    public function accountData($acc_id) {
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

    public function insertAccountUsers() {
        $acc_status = 1;
        $acc_permission = 'u';
        $query = "INSERT INTO " . $this->table_name . " 
        (username, password,acc_permission,acc_status) 
        VALUES (:username, :password, :acc_permission,:acc_status)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':acc_permission', $acc_permission);
        $stmt->bindParam(':acc_status', $acc_status);
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function insertAccountEmployees() {
        // สร้างบัญชีผู้ใช้ก่อน
        $acc_status = 1;
        $acc_permission = 'e';
        $query = "INSERT INTO " . $this->table_name . " 
        (username, password, acc_permission, acc_status) 
        VALUES (:username, :password, :acc_permission, :acc_status)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':acc_permission', $acc_permission);
        $stmt->bindParam(':acc_status', $acc_status);
    
        try {
            if ($stmt->execute()) {
                $acc_id = $this->conn->lastInsertId();
    
                // เพิ่มข้อมูลพนักงาน
                $emp_status = '1';
                $query = "INSERT INTO tb_employees 
                (emp_name, emp_phone, emp_address, emp_status, acc_id) 
                VALUES (:emp_name, :emp_phone, :emp_address, :emp_status, :acc_id)";
    
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':emp_name', $this->emp_name);
                $stmt->bindParam(':emp_phone', $this->emp_phone);
                $stmt->bindParam(':emp_address', $this->emp_address);
                $stmt->bindParam(':emp_status', $emp_status);
                $stmt->bindParam(':acc_id', $acc_id);
    
                if ($stmt->execute()) {
                    return true; // เปลี่ยนเป็น return true
                } else {
                    throw new Exception("Failed to insert employee details.");
                }
            } else {
                throw new Exception("Failed to create account.");
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    

    public function getAccount($acc_status,$acc_permission)
    {
        try {
            if($acc_permission == 'u'){
            $query = " SELECT a.*,u.* FROM {$this->table_name} AS a
                      JOIN tb_users AS u ON a.acc_id = u.acc_id 
                     WHERE a.acc_status = :acc_status AND a.acc_permission = :acc_permission ";
            }elseif($acc_permission == 'e'){
                $query = " SELECT a.*,e.* FROM {$this->table_name} AS a
                JOIN tb_employees AS e ON a.acc_id = e.acc_id 
               WHERE a.acc_status = :acc_status AND a.acc_permission = :acc_permission ";
            }else{
                $query = " SELECT * FROM {$this->table_name} 
               WHERE acc_status = :acc_status AND acc_permission = :acc_permission ";
            }
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':acc_status', $acc_status);
            $stmt->bindParam(':acc_permission', $acc_permission);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // จัดการกับข้อผิดพลาด เช่น บันทึกข้อผิดพลาดลงไฟล์ล็อก หรือแสดงข้อความข้อผิดพลาด
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function getAccountUserById($id)
    {
        try {
            $query = "SELECT a.*,u.* FROM {$this->table_name} AS a
                     JOIN tb_users AS u ON a.acc_id = u.acc_id 
                     WHERE  a.acc_permission = 'u' AND a.acc_id = $id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // จัดการกับข้อผิดพลาด เช่น บันทึกข้อผิดพลาดลงไฟล์ล็อก หรือแสดงข้อความข้อผิดพลาด
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function getAccountEmployeeById($id)
    {
        try {
            $query = "SELECT a.*,e.* FROM {$this->table_name} AS a
                     JOIN tb_employees AS e ON a.acc_id = e.acc_id 
                     WHERE  a.acc_permission = 'e' AND a.acc_id = $id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // จัดการกับข้อผิดพลาด เช่น บันทึกข้อผิดพลาดลงไฟล์ล็อก หรือแสดงข้อความข้อผิดพลาด
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
    
    public function updateAccountUsers($acc_id)
    {
        // ตรวจสอบค่าว่างเปล่า
        if (empty($this->username) || empty($this->password) ) {
            throw new Exception("All fields are required.");
        }

        $query = "UPDATE {$this->table_name} SET username = :username, password = :password  WHERE acc_id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $acc_id, PDO::PARAM_INT);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);

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

    public function updateAccountEmployees($acc_id, $emp_id)
    {
        // ตรวจสอบค่าว่างเปล่าสำหรับบัญชีผู้ใช้
        if (empty($this->username) || empty($this->password)) {
            throw new Exception("All fields are required for account.");
        }
    
        try {
            // อัปเดตข้อมูลบัญชีผู้ใช้
            $query = "UPDATE {$this->table_name} SET username = :username, password = :password WHERE acc_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $acc_id, PDO::PARAM_INT);
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':password', $this->password);
    
            if ($stmt->execute()) {
                // ตรวจสอบค่าว่างเปล่าสำหรับพนักงาน
                if (empty($this->emp_name) || empty($this->emp_phone) || empty($this->emp_address)) {
                    throw new Exception("All fields are required for employee.");
                }
    
                // อัปเดตข้อมูลพนักงาน
                $query = "UPDATE tb_employees SET emp_name = :emp_name, emp_phone = :emp_phone, emp_address = :emp_address WHERE emp_id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $emp_id, PDO::PARAM_INT);
                $stmt->bindParam(':emp_name', $this->emp_name);
                $stmt->bindParam(':emp_phone', $this->emp_phone);
                $stmt->bindParam(':emp_address', $this->emp_address);
    
                if ($stmt->execute()) {
                    return true; // หากการอัปเดตทั้งสองสำเร็จ
                } else {
                    throw new Exception("Failed to update employee details.");
                }
            } else {
                throw new Exception("Failed to update account details.");
            }
        } catch (PDOException $e) {
            throw new Exception("Failed to update account and employee details: " . $e->getMessage());
        }
    }
    

    public function Logout() {
        session_start();
        unset($_SESSION['acc_id']);
        // session_destroy();
        header("Location: login.php");
        exit;
    }


    public function deleteAccount($acc_id)
    {
        $query = "UPDATE {$this->table_name} SET acc_status = '0'  WHERE acc_id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $acc_id, PDO::PARAM_INT);

        try {
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Failed to update employee: " . $e->getMessage());
        }
    }

    public function restoreAccount($acc_id)
    {
        $query = "UPDATE {$this->table_name} SET acc_status = '1'  WHERE acc_id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $acc_id, PDO::PARAM_INT);

        try {
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Failed to update employee: " . $e->getMessage());
        }
    }
    
}


?>
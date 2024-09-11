<?php
class RepairRequest
{
    private $conn;
    private $table_name = "tb_repair_requests";

    public $id;
    public $req_heading;
    public $req_detail;
    public $user_id;
    public $created_by;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function insertRepairRequest()
    {
        $req_status = 0;
        $query = "INSERT INTO " . $this->table_name . " 
        (req_heading, req_status, created_by,user_id,req_detail) 
        VALUES (:req_heading, :req_status, :created_by,:user_id,:req_detail)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':req_heading', $this->req_heading);
        $stmt->bindParam(':req_detail', $this->req_detail);
        $stmt->bindParam(':req_status', $req_status);
        $stmt->bindParam(':created_by', $this->created_by);
        $stmt->bindParam(':user_id', $this->user_id);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function getRepairRequest($req_status, $user_id)
    {
        try {
            if ($user_id == 'x') {
                $query = "SELECT r.*,u.*,a.* FROM {$this->table_name} AS r
                         JOIN tb_users AS u ON r.user_id = u.user_id
                         JOIN tb_accommodations AS a ON u.amd_id = a.amd_id
                         WHERE r.req_status = :req_status ";
            } else {
                $query = "SELECT * FROM {$this->table_name} WHERE req_status = :req_status AND user_id = :user_id";
            }
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':req_status', $req_status);

            if ($user_id != 'x') {
                $stmt->bindParam(':user_id', $user_id);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // จัดการกับข้อผิดพลาด เช่น บันทึกข้อผิดพลาดลงไฟล์ล็อก หรือแสดงข้อความข้อผิดพลาด
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    public function getRepairRequestSchedule($req_status, $user_id)
    {
        try {
            if ($user_id == 'x') {
                $query = "SELECT r.*,s.*,e.*,u.*,a.* FROM {$this->table_name} AS r
                JOIN tb_schedule AS s ON r.req_id = s.req_id 
                JOIN tb_users AS u ON r.user_id = u.user_id 
                JOIN tb_employees AS e ON s.emp_id = e.emp_id
                JOIN tb_accommodations AS a ON u.amd_id = a.amd_id
                WHERE r.req_status = :req_status ";
            } else {
                $query = "SELECT r.*,s.*,e.* FROM {$this->table_name} AS r
                      JOIN tb_schedule AS s ON r.req_id = s.req_id 
                      JOIN tb_employees AS e ON s.emp_id = e.emp_id
                      WHERE r.req_status = :req_status AND r.user_id = :user_id";
            }
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':req_status', $req_status);
            if ($user_id != 'x') {
                $stmt->bindParam(':user_id', $user_id);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // จัดการกับข้อผิดพลาด เช่น บันทึกข้อผิดพลาดลงไฟล์ล็อก หรือแสดงข้อความข้อผิดพลาด
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    public function getRepairRequestScheduleForEmployee($req_status, $emp_id)
    {
        try {
            $query = "SELECT r.*,s.*,e.*,u.* FROM {$this->table_name} AS r
                      JOIN tb_schedule AS s ON r.req_id = s.req_id 
                      JOIN tb_employees AS e ON s.emp_id = e.emp_id
                      JOIN tb_users AS u ON r.user_id = u.user_id
                      WHERE r.req_status = :req_status AND s.emp_id = :emp_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':req_status', $req_status);
            $stmt->bindParam(':emp_id', $emp_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // จัดการกับข้อผิดพลาด เช่น บันทึกข้อผิดพลาดลงไฟล์ล็อก หรือแสดงข้อความข้อผิดพลาด
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    public function getRepairRequestForEmployee($req_status)
    {
        try {
            $query = "SELECT r.*, u.*,a.* FROM {$this->table_name} AS r
                      LEFT JOIN tb_users AS u ON r.user_id = u.user_id
                      JOIN tb_accommodations AS a ON u.amd_id = a.amd_id
                      WHERE r.req_status = :req_status";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':req_status', $req_status);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }



    public function getRepairRequestById($id)
    {
        try {
            $query = "SELECT r.*,u.*,a.* FROM {$this->table_name} AS r
                      JOIN tb_users AS u ON r.user_id = u.user_id
                      JOIN tb_accommodations AS a ON u.amd_id = a.amd_id
                      WHERE r.req_status = '0' AND r.req_id = $id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // จัดการกับข้อผิดพลาด เช่น บันทึกข้อผิดพลาดลงไฟล์ล็อก หรือแสดงข้อความข้อผิดพลาด
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function getRepairRequestConfirmById($id)
    {
        try {
            $query = "SELECT r.*,u.*,s.*,e.*,a.* FROM {$this->table_name} AS r
                      JOIN tb_users AS u ON r.user_id = u.user_id
                      JOIN tb_schedule AS s ON r.req_id = s.req_id
                      JOIN tb_employees AS e ON s.emp_id = e.emp_id
                      JOIN tb_accommodations AS a ON u.amd_id = a.amd_id
                      WHERE  r.req_id = $id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // จัดการกับข้อผิดพลาด เช่น บันทึกข้อผิดพลาดลงไฟล์ล็อก หรือแสดงข้อความข้อผิดพลาด
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function getImagesById($id)
    {
        try {
            $query = "SELECT * FROM tb_images WHERE req_id = $id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // จัดการกับข้อผิดพลาด เช่น บันทึกข้อผิดพลาดลงไฟล์ล็อก หรือแสดงข้อความข้อผิดพลาด
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function getImageSuccessById($id)
    {
        try {
            $query = "SELECT * FROM tb_images_success WHERE req_id = $id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // จัดการกับข้อผิดพลาด เช่น บันทึกข้อผิดพลาดลงไฟล์ล็อก หรือแสดงข้อความข้อผิดพลาด
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function updateRepairRequest($id, $heading, $detail, $created_by, $user_id)
    {
        $query = "UPDATE {$this->table_name}
                  SET req_heading = :heading, req_detail = :detail, created_by = :created_by, user_id = :user_id 
                  WHERE req_id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':heading', $heading);
            $stmt->bindParam(':detail', $detail);
            $stmt->bindParam(':created_by', $created_by);
            $stmt->bindParam(':user_id', $user_id);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function deleteImageById($img_id)
    {
        $query = "DELETE FROM tb_images WHERE img_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $img_id);
        $stmt->execute();
    }

    public function updateImage($request_id, $img_path)
    {
        $query = "INSERT INTO tb_images (req_id, img_path) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $request_id);
        $stmt->bindParam(2, $img_path);
        $stmt->execute();
    }

    public function insertImageSuccess($req_id, $imgs_path)
    {
        try {
            // เริ่มการทำธุรกรรม
            $this->conn->beginTransaction();

            // แทรกข้อมูลเข้าไปใน tb_images_success
            $query = "INSERT INTO tb_images_success (req_id, imgs_path) VALUES (?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $req_id);
            $stmt->bindParam(2, $imgs_path);

            if (!$stmt->execute()) {
                throw new Exception("Failed to insert image success.");
            }

            // อัปเดตสถานะในตารางหลัก
            $req_status = 2; // กำหนดสถานะที่ต้องการอัปเดต
            $query = "UPDATE {$this->table_name} SET req_status = :req_status WHERE req_id = :req_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':req_status', $req_status);
            $stmt->bindParam(':req_id', $req_id);

            if (!$stmt->execute()) {
                throw new Exception("Failed to update request status.");
            }

            // ถ้าทุกอย่างผ่านไปด้วยดี ให้ commit
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            // ยกเลิกการทำธุรกรรมหากเกิดข้อผิดพลาด
            $this->conn->rollBack();
            echo "Error: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            // ยกเลิกการทำธุรกรรมหากเกิดข้อผิดพลาดทั่วไป
            $this->conn->rollBack();
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}

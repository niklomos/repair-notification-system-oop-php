<?php 
class Schedule{
    private $conn;
    private $table_name = "tb_schedule";

    public $sch_date;
    public $emp_id;
    public $req_id;
    public $updated_by;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function insertSchedule() {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                      (sch_date, emp_id, req_id) 
                      VALUES (:sch_date, :emp_id, :req_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':sch_date', $this->sch_date);
            $stmt->bindParam(':emp_id', $this->emp_id);
            $stmt->bindParam(':req_id', $this->req_id);

            if ($stmt->execute()) {
                $req_status = 1;
                $query = "UPDATE tb_repair_requests SET req_status = :req_status,updated_by = :updated_by,updated_at = NOW() WHERE req_id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $this->req_id);
                $stmt->bindParam(':req_status', $req_status);
                $stmt->bindParam(':updated_by', $this->updated_by);

                if ($stmt->execute()) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}

?>
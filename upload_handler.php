<?php
include_once 'class/Database.php';
include_once 'class/RepairRequest.php';

$database = new Database();
$db = $database->getConnection();

$request = new RepairRequest($db);
$request->req_heading = $_POST['req_heading'];
$request->req_detail = $_POST['req_detail'];
$request->created_by = $_POST['created_by'];
$request->user_id = $_POST['user_id'];


$request_id = $request->insertRepairRequest();

if ($request_id) {
    $target_dir = "uploads/";
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['images']['name'][$key]);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($tmp_name, $target_file)) {
            $query = "INSERT INTO tb_images (req_id, img_path) VALUES (:req_id, :img_path)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':req_id', $request_id);
            $stmt->bindParam(':img_path', $target_file);
            $result = $stmt->execute();
        }
    }
    if ($result) {
        echo "<script>
    alert('เพิ่มข้อมูลสำเร็จ');
    window.location.href = 'summary_user.php';
            </script>";
        exit;
    }
}



echo json_encode($response);

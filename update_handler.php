<?php
include_once 'class/Database.php';
include_once 'class/RepairRequest.php';

$database = new Database();
$db = $database->getConnection();

$request = new RepairRequest($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $id = $_POST['req_id'];
    $req_heading = $_POST['req_heading'];
    $req_detail = $_POST['req_detail'];
    $created_by = $_POST['created_by'];
    $user_id = $_POST['user_id'];
    $delete_images = isset($_POST['delete_images']) ? $_POST['delete_images'] : [];

    // อัปเดตรายการซ่อม
    $request->updateRepairRequest($id, $req_heading, $req_detail, $created_by, $user_id);

    // ลบรูปภาพที่เลือก
    foreach ($delete_images as $img_id) {
        $request->deleteImageById($img_id);
    }

    // อัปโหลดรูปภาพใหม่ถ้ามี
    if (!empty($_FILES['images']['name'][0])) {
        $uploadDir = 'uploads/';
        foreach ($_FILES['images']['name'] as $key => $val) {
            $uploadFile = $uploadDir . basename($_FILES['images']['name'][$key]);
            move_uploaded_file($_FILES['images']['tmp_name'][$key], $uploadFile);
            $request->updateImage($id, $uploadFile);
        }
    }

    // Redirect or show success message
    header("Location: summary_user.php");
}
?>

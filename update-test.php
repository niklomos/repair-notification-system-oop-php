<?php
include_once 'class/Database.php';
include_once 'class/Accommodation.php';

$database = new Database();
$db = $database->getConnection();

$accommodation = new Accommodation($db);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $amd = $accommodation->getAccommodationById($id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $accommodation->amd_name = $_POST['amd_name'];
        $accommodation->amd_type = $_POST['amd_type'];
        $accommodation->amd_address = $_POST['amd_address'];
        $amd_id = $_POST['amd_id'];
        $result = $accommodation->updateAccommodation($amd_id);

        $response = array();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'อัปเดตข้อมูลสำเร็จ';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'อัปเดตข้อมูลไม่สำเร็จ';
        }
        echo json_encode($response);
        exit;
    }


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['inactive_id'])) {
    $amd_id = htmlspecialchars($_GET['inactive_id']);
    
    $response = array();
    if ($accommodation->deleteAccommodation($amd_id)) {
        $response['status'] = 'success';
        $response['message'] = 'ลบข้อมูลสำเร็จ';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'ลบข้อมูลไม่สำเร็จ';
    }
    echo json_encode($response);
    exit;
}
?>

<?php
session_start();
include_once 'class/database.php';
include_once 'class/Account.php';

// รับค่าจากฟอร์ม
$username = htmlspecialchars($_POST['username']);
$password = htmlspecialchars($_POST['password']);

// สร้างการเชื่อมต่อฐานข้อมูล
$database = new Database();
$db = $database->getConnection();

// Check Account
$account = new Account($db);
if ($account->checkAccount($username, $password)) {
    $acc_permission = $_SESSION['acc_permission'];
    if($acc_permission == 'u'){
        header("Location: summary_user.php");
    }elseif($acc_permission == 'e'){
        header("Location: summary_employee.php");
    }else{
        header("Location: summary_admin.php");
    }
    exit;
} else {
    // Redirect back to the login page with an error message
    $_SESSION['error'] = 'Invalid username or password';
    header("Location: login.php");
    exit;
}
?>

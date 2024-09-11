<?php
include_once 'class/Database.php';
include_once 'class/User.php';

$database = new Database();
$db = $database->getConnection();

$users = new User($db);

    $users->user_name = $_POST['user_name'];
    $users->user_phone = $_POST['user_phone'];
    $users->user_room = $_POST['user_room'];
    $user_id = $_POST['user_id'];

    $result = $users->updateProfileUser($user_id);

        $nik=[
            'success' => $result,
            'message' => $result ? 'ดีมาก' :'ควย'
        ];
        echo json_encode($nik);
  

?>

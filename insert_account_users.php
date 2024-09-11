<?php
include_once("sys_header.inc.php");
ob_start();
?>
<?php
include_once 'class/Database.php';
include_once 'class/Account.php';
include_once 'class/User.php';
include_once 'class/Accommodation.php';
$database = new Database();
$db = $database->getConnection();

$accommodation = new Accommodation($db);
$amd_status = 1;
$accommodation = $accommodation->getAccommodation($amd_status);

if(isset($_POST['submit'])){


$account = new Account($db);
$user = new User($db);



$account->username = $_POST['username'];
$account->password = $_POST['password'];
$acc_id = $account->insertAccountUsers();

if($acc_id){
$user->acc_id = $acc_id;
$user->user_name = $_POST['user_name'];
$user->user_phone = $_POST['user_phone'];
$user->user_room = $_POST['user_room'];
$user->amd_id = $_POST['amd_id'];

$result = $user->insertUsers();
if ($result) {
    echo "<script>
    alert('เพิ่มข้อมูลสำเร็จ');
    window.location.href = 'manage_account_users.php';
</script>";
    exit;
}
}
}

?>
<div class="container pb-3 ">
    <h1 class="mb-4">Insert Account Users</h1>
    <form  action="" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="user_name" class="form-label">Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="user_name" name="user_name" required>
        </div>
        <div class="mb-3">
            <label for="user_phone" class="form-label">Phone<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="user_phone" name="user_phone"  required>
        </div>
       
        <div class="mb-3">
            <label for="user_room" class="form-label">Room<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="user_room" name="user_room"  required>
        </div>
        <div class="mb-3">
            <label class="form-label">Address<span class="text-danger">*</span></label>
            <select class="form-control select2bs4 " id="amd_id" name="amd_id" style="width: 100%;" required>
                <option value="">Select a address...</option>
                <?php foreach ($accommodation as $amd) : ?>
                    <option value="<?php echo $amd['amd_id']; ?>"><?php echo $amd['amd_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add</button>
    </form>
</div>

<script src="plugins/select2/js/select2.full.min.js"></script>

<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

    })
</script>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
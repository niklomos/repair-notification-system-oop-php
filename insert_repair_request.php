<?php
include_once("sys_header.inc.php");
ob_start();
?>
<?php
include_once 'class/User.php';
include_once 'class/Database.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if (isset($_SESSION['acc_id'])) {
    $acc_id = $_SESSION['acc_id'];
    $users = $user->userData($acc_id);
}
?>
<div class="container ">
    <h1 class="mb-4">Insert Repair</h1>
    <form id="repairForm" action="upload_handler.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="req_heading" class="form-label">Heading<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="req_heading" name="req_heading" required>
        </div>
        <div class="mb-3">
            <label for="req_detail" class="form-label">Detail<span class="text-danger">*</span></label>
            <textarea class="form-control" id="req_detail" name="req_detail" required></textarea>
        </div>
        <div class="mb-3">
            <input type="hidden" class="form-control" id="created_by" name="created_by" value="<?php echo $users['user_id'] ?>" required>
            <input type="hidden" class="form-control" id="user_id" name="user_id" value="<?php echo $users['user_id'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="images" class="form-label">Images<span class="text-danger">*</span></label>
            <input type="file" class="form-control" id="images" name="images[]" multiple required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add</button>
    </form>
</div>


<?php
$content = ob_get_clean();
include 'layout.php';
?>
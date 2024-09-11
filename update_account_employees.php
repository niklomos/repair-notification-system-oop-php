<?php
include_once("sys_header.inc.php");
ob_start();
?>
<?php
include_once 'class/Database.php';
include_once 'class/Account.php';

$database = new Database();
$db = $database->getConnection();

$account = new Account($db);

$id = $_GET["id"];
$acc = $account->getAccountEmployeeById($id); // ใช้ getEmployeeById แทน getEmployeesById

if (isset($_POST['update'])) {

    $acc_id = $_POST['acc_id'];
    $emp_id = $_POST['emp_id'];
    $account->username = $_POST['username'];
    $account->password = $_POST['password'];
    $account->emp_name = $_POST['emp_name'];
    $account->emp_phone = $_POST['emp_phone'];
    $account->emp_address = $_POST['emp_address'];
    $result= $account->updateAccountEmployees($acc_id,$emp_id);

    if ($result) {
        echo "<script>
        alert('อัปเดตข้อมูลสำเร็จ');
        window.location.href = 'manage_account_employees.php';
    </script>";
        exit;
    }
}

if (isset($_GET['inactive_id'])) {

    // รับค่าจากฟอร์ม
    $acc_id = htmlspecialchars($_GET['inactive_id']);
    
    if ($account->deleteAccount($acc_id)) {
        header("Location: manage_account_employees.php");
            exit; // ใช้ exit เพื่อหยุดการทำงานของสคริปต์หลังจาก redirect
    } else {
        echo "Failed to delete account.";
    }
    }
?>
<div class="container pb-3">
    <h1 class="mb-4">Update Account Employee</h1>
    <form action="" method="post">
        <input type="hidden" name="acc_id" value="<?php echo htmlspecialchars($acc['acc_id']); ?>">
        <input type="hidden" name=" emp_id" value="<?php echo htmlspecialchars($acc['emp_id']); ?>">
        <div class="mb-3">
            <label for="username" class="form-label">Username<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($acc['username']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($acc['password']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="emp_name" class="form-label">Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="emp_name" name="emp_name" value="<?php echo htmlspecialchars($acc['emp_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="emp_phone" class="form-label">Phone<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="emp_phone" name="emp_phone" value="<?php echo htmlspecialchars($acc['emp_phone']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="emp_address" class="form-label">Address<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="emp_address" name="emp_address" value="<?php echo htmlspecialchars($acc['emp_address']); ?>" required>
        </div>
        <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Update</button>
        <a href="#" class="btn btn-danger b-button" id="inactiveButton"><i class="fas fa-trash-alt me-1"></i>Inactive</a>
    </form>
</div>

<script>
document.getElementById('inactiveButton').addEventListener('click', function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to delete this account user ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            // Construct the URL for deletion
            window.location.href  = '<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>?inactive_id=<?php echo htmlspecialchars($acc['acc_id']); ?>';
            // Redirect to the delete URL
        }
    });
});
</script>
<?php
$content = ob_get_clean();
include 'layout.php';
?>
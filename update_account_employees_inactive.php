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

    if (isset($_GET['active_id'])) {

        // รับค่าจากฟอร์ม
        $acc_id = htmlspecialchars($_GET['active_id']);
        
        if ($account->restoreAccount($acc_id)) {
            header("Location: recycle_account_employees.php");
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
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($acc['username']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="text" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($acc['password']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="emp_name" class="form-label">Name</label>
            <input type="text" class="form-control" id="emp_name" name="emp_name" value="<?php echo htmlspecialchars($acc['emp_name']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="emp_phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="emp_phone" name="emp_phone" value="<?php echo htmlspecialchars($acc['emp_phone']); ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="emp_address" class="form-label">Address</label>
            <input type="text" class="form-control" id="emp_address" name="emp_address" value="<?php echo htmlspecialchars($acc['emp_address']); ?>" disabled>
        </div>
        <a href="#" class="btn btn-success" id="activeButton"><i class="fas fa-refresh me-1"></i>Active</a>
    </form>
</div>

<script>
document.getElementById('activeButton').addEventListener('click', function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to restore this account employee ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, restore it!',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            // Construct the URL for deletion
            window.location.href  = '<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>?active_id=<?php echo htmlspecialchars($acc['acc_id']); ?>';
            // Redirect to the delete URL
        }
    });
});
</script>
<?php
$content = ob_get_clean();
include 'layout.php';
?>
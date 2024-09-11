<?php
include_once("sys_header.inc.php");
ob_start();
?>
<?php
include_once 'class/Database.php';
include_once 'class/Account.php';
include_once 'class/User.php';

$database = new Database();
$db = $database->getConnection();

$account = new Account($db);
$user = new User($db);

$id = $_GET["id"];
$acc = $account->getAccountUserById($id); // ใช้ getEmployeeById แทน getEmployeesById

if (isset($_GET['active_id'])) {

    // รับค่าจากฟอร์ม
    $acc_id = htmlspecialchars($_GET['active_id']);

    if ($account->restoreAccount($acc_id)) {
        header("Location: recycle_account_users.php");
        exit; // ใช้ exit เพื่อหยุดการทำงานของสคริปต์หลังจาก redirect
    } else {
        echo "Failed to delete account.";
    }
}
?>
<div class="container pb-3 ">
    <h1 class="mb-4">Update Account User</h1>
    <form action="" method="post">
        <input type="hidden" name="acc_id" value="<?php echo htmlspecialchars($acc['acc_id']); ?>">
        <input type="hidden" name=" user_id" value="<?php echo htmlspecialchars($acc['user_id']); ?>">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($acc['username']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="text" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($acc['password']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="user_name" class="form-label">Name</label>
            <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo htmlspecialchars($acc['user_name']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="user_phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="user_phone" name="user_phone" value="<?php echo htmlspecialchars($acc['user_phone']); ?>" disabled>
        </div>

        <div class="mb-3">
            <label for="user_room" class="form-label">Room</label>
            <input type="text" class="form-control" id="user_room" name="user_room" value="<?php echo htmlspecialchars($acc['user_room']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="user_address" class="form-label">Address</label>
            <input type="text" class="form-control" id="user_address" name="user_address" value="สิริรัชต์ แมนชั่น" disabled>
        </div>
        <a href="#" class="btn btn-success" id="activeButton"><i class="fas fa-refresh me-1"></i>Active</a>
    </form>
</div>

<script>
    document.getElementById('activeButton').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to restore this account user ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, restore it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                // Construct the URL for deletion
                window.location.href = '<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>?active_id=<?php echo htmlspecialchars($acc['acc_id']); ?>';
                // Redirect to the delete URL
            }
        });
    });
</script>
<?php
$content = ob_get_clean();
include 'layout.php';
?>
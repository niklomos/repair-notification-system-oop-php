<?php
include_once("sys_header.inc.php");
ob_start();
?>
<?php
include_once 'class/Database.php';
include_once 'class/Account.php';

if (isset($_POST['submit'])) {

    $database = new Database();
    $db = $database->getConnection();

    $account = new Account($db);

    $account->username = $_POST['username'];
    $account->password = $_POST['password'];
    $account->emp_name = $_POST['emp_name'];
    $account->emp_phone = $_POST['emp_phone'];
    $account->emp_address = $_POST['emp_address'];
    $result = $account->insertAccountEmployees();

    if ($result) {
        echo "<script>
    alert('เพิ่มข้อมูลสำเร็จ');
    window.location.href = 'manage_account_employees.php';
</script>";
        exit;
    }
}

?>
<div class="container  pb-3">
    <h1 class="mb-4">Insert Account Employees</h1>
    <form action="" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="emp_name" class="form-label">Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="emp_name" name="emp_name" required>
        </div>
        <div class="mb-3">
            <label for="emp_phone" class="form-label">Phone<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="emp_phone" name="emp_phone" required>
        </div>

        <div class="mb-3">
            <label for="emp_address" class="form-label">Address<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="emp_address" name="emp_address" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add</button>
    </form>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
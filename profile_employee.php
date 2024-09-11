<?php
include_once("sys_header.inc.php");
ob_start();
?>
<?php
include_once 'class/Database.php';
include_once 'class/Employee.php';

$database = new Database();
$db = $database->getConnection();

//แสดงข้อมูล ตาม ID
$employee = new Employee($db);
$id = $_GET["id"];
$emp = $employee->getEmployeeById($id);

if (isset($_POST['update'])) {

    $employee->emp_name = $_POST['emp_name'];
    $employee->emp_phone = $_POST['emp_phone'];
    $employee->emp_address = $_POST['emp_address'];
    $emp_id = $_POST['emp_id'];
    $result = $employee->updateProfileEmployee($emp_id);

    if ($result) {
        echo "<script>
             alert('เพิ่มข้อมูลสำเร็จ');
             window.location.href = 'summary_employee.php';
             </script>";
        exit;
    }
}

?>
<div class="container ">
    <h1 class="mb-4">Profile</h1>
    <form action="" method="post">
        <input type="hidden" name="emp_id" value="<?php echo htmlspecialchars($emp['emp_id']); ?>">
        <div class="mb-3">
            <label for="emp_name" class="form-label">Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="emp_name" name="emp_name" value="<?php echo htmlspecialchars($emp['emp_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="emp_phone" class="form-label">Phone<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="emp_phone" name="emp_phone" value="<?php echo htmlspecialchars($emp['emp_phone']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="emp_address" class="form-label">Address<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="emp_address" name="emp_address" value="<?php echo htmlspecialchars($emp['emp_address']); ?>" required>
        </div>
        <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Update</button>
    </form>
</div>


<?php
$content = ob_get_clean();
include 'layout.php';
?>
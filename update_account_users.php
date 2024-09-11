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

$account = new Account($db);
$user = new User($db);

$id = $_GET["id"];
$acc = $account->getAccountUserById($id); // ใช้ getEmployeeById แทน getEmployeesById

if(isset($_POST['update'])){

    $account->username = $_POST['username'];
    $account->password = $_POST['password'];
    $acc_id = $_POST['acc_id'];
    $result_acc = $account->updateAccountUsers($acc_id);


    $user->user_name = $_POST['user_name'];
    $user->user_phone = $_POST['user_phone'];
    $user->user_room = $_POST['user_room'];
    $user->amd_id = $_POST['amd_id'];
    $user_id = $_POST['user_id'];
    $result_user = $user->updateAccountUsers($user_id);

        if ($result_acc && $result_user) {
        echo "<script>
        alert('อัปเดตข้อมูลสำเร็จ');
        window.location.href = 'manage_account_users.php';
    </script>";
        exit;
    }
    }

    if (isset($_GET['inactive_id'])) {

        // รับค่าจากฟอร์ม
        $acc_id = htmlspecialchars($_GET['inactive_id']);
        
        if ($account->deleteAccount($acc_id)) {
            header("Location: manage_account_users.php");
                exit; // ใช้ exit เพื่อหยุดการทำงานของสคริปต์หลังจาก redirect
        } else {
            echo "Failed to delete account.";
        }
        }
?>
<div class="container pb-3 ">
    <h1 class="mb-4">Update Account User</h1>
    <form  action="" method="post">
    <input type="hidden" name="acc_id" value="<?php echo htmlspecialchars($acc['acc_id']); ?>">
    <input type="hidden" name=" user_id" value="<?php echo htmlspecialchars($acc['user_id']); ?>">
        <div class="mb-3">
            <label for="username" class="form-label">Username<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($acc['username']); ?>"  required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($acc['password']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="user_name" class="form-label">Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo htmlspecialchars($acc['user_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="user_phone" class="form-label">Phone<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="user_phone" name="user_phone" value="<?php echo htmlspecialchars($acc['user_phone']); ?>"  required>
        </div>
       
        <div class="mb-3">
            <label for="user_room" class="form-label">Room<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="user_room" name="user_room" value="<?php echo htmlspecialchars($acc['user_room']); ?>"  required>
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
<script>
    // หา element select โดยใช้ id
    var selectElement = document.getElementById('amd_id');
    // ค่า dep_id ของ Position
    var userAmdId = <?php echo htmlspecialchars($acc['amd_id']); ?>;

    // วนลูปผ่านตัวเลือกทั้งหมดใน select
    for (var i = 0; i < selectElement.options.length; i++) {
        // ตรวจสอบว่า pos_id ของตัวเลือกตรงกับ pos_id ของ Employee หรือไม่
        if (selectElement.options[i].value == userAmdId) {
            // กำหนด selected attribute ให้กับตัวเลือกที่ตรงกัน
            selectElement.options[i].selected = true;
            // หยุดการวนลูปหากเจอการตรงค่า
            break;
        }
    }
</script>
<?php
$content = ob_get_clean();
include 'layout.php';
?>
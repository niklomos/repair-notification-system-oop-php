<?php
include_once("sys_header.inc.php");
ob_start();
?>

<?php
include_once 'class/Database.php';
include_once 'class/Accommodation.php';

$database = new Database();
$db = $database->getConnection();

$accommodation = new Accommodation($db);

$id = $_GET["id"];
$amd = $accommodation->getAccommodationById($id); 

if(isset($_POST['update'])){

    $accommodation->amd_name = $_POST['amd_name'];
    $accommodation->amd_type = $_POST['amd_type'];
    $accommodation->amd_address = $_POST['amd_address'];
    $amd_id = $_POST['amd_id'];
    $result = $accommodation->updateAccommodation($amd_id);
    if ($result) {
        echo "<script>
        $(document).ready(function() {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'อัปเดตข้อมูลสำเร็จ',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'manage_accommodations.php';
                }
            });
        });
        </script>";
        exit;
    } else {
        echo "<script>
        $(document).ready(function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'อัปเดตข้อมูลไม่สำเร็จ',
                confirmButtonText: 'OK'
            });
        });
        </script>";
    }
}

    if (isset($_GET['inactive_id'])) {

        // รับค่าจากฟอร์ม
        $amd_id = htmlspecialchars($_GET['inactive_id']);
        
        if ($accommodation->deleteAccommodation($amd_id)) {
            header("Location: manage_accommodations.php");
                exit; // ใช้ exit เพื่อหยุดการทำงานของสคริปต์หลังจาก redirect
        } else {
            echo "Failed to delete accommodation.";
        }
        }
?>
<div class="container pb-3 ">
    <h1 class="mb-4">Update Accommodation</h1>
    <form  action="" method="post">
    <input type="hidden" name="amd_id" value="<?php echo htmlspecialchars($amd['amd_id']); ?>">
        <div class="mb-3">
            <label for="amd_name" class="form-label">Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="amd_name" name="amd_name" value="<?php echo htmlspecialchars($amd['amd_name']); ?>"  required>
        </div>
        <div class="mb-3">
            <label for="amd_type" class="form-label">Type<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="amd_type" name="amd_type" value="<?php echo htmlspecialchars($amd['amd_type']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="amd_address" class="form-label">Address<span class="text-danger">*</span></label>
            <textarea class="form-control" id="amd_address" name="amd_address" rows="5" cols="50" required><?php echo htmlspecialchars($amd['amd_address']); ?></textarea>
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
        text: 'Do you want to delete this accommodation ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            // Construct the URL for deletion
            window.location.href  = '<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>?inactive_id=<?php echo htmlspecialchars($amd['amd_id']); ?>';
            // Redirect to the delete URL
        }
    });
});
</script>
<?php
$content = ob_get_clean();
include 'layout.php';
?>
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


    if (isset($_GET['active_id'])) {

        // รับค่าจากฟอร์ม
        $amd_id = htmlspecialchars($_GET['active_id']);
        
        if ($accommodation->restoreAccommodation($amd_id)) {
            header("Location: recycle_accommodations.php");
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
            <input type="text" class="form-control" id="amd_name" name="amd_name" value="<?php echo htmlspecialchars($amd['amd_name']); ?>"  disabled>
        </div>
        <div class="mb-3">
            <label for="amd_type" class="form-label">Type<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="amd_type" name="amd_type" value="<?php echo htmlspecialchars($amd['amd_type']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="amd_address" class="form-label">Address<span class="text-danger">*</span></label>
            <textarea class="form-control" id="amd_address" name="amd_address" rows="5" cols="50" disabled><?php echo htmlspecialchars($amd['amd_address']); ?></textarea>
        </div>
        <a href="#" class="btn btn-success" id="activeButton"><i class="fas fa-refresh me-1"></i>Active</a>
    </form>
</div>

<script>
document.getElementById('activeButton').addEventListener('click', function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to restore this accommodation ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, restore it!',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            // Construct the URL for deletion
            window.location.href  = '<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>?active_id=<?php echo htmlspecialchars($amd['amd_id']); ?>';
            // Redirect to the delete URL
        }
    });
});
</script>
<?php
$content = ob_get_clean();
include 'layout.php';
?>
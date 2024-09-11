<?php
include_once("sys_header.inc.php");
ob_start();
?>
<?php
include_once 'class/Database.php';
include_once 'class/Accommodation.php';

if (isset($_POST['submit'])) {

    $database = new Database();
    $db = $database->getConnection();

    $accommodation = new Accommodation($db);

    $accommodation->amd_name = $_POST['amd_name'];
    $accommodation->amd_type = $_POST['amd_type'];
    $accommodation->amd_address = $_POST['amd_address'];
    $result = $accommodation->insertAccommodation();

    if ($result) {
        echo "<script>
    alert('เพิ่มข้อมูลสำเร็จ');
    window.location.href = 'manage_accommodations.php';
</script>";
        exit;
    }
}

?>
<div class="container  pb-3">
    <h1 class="mb-4">Insert Accommodation</h1>
    <form action="" method="post">
        <div class="mb-3">
            <label for="amd_name" class="form-label">Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="amd_name" name="amd_name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Type<span class="text-danger">*</span></label>
            <select class="form-control select2bs4 " id="amd_type" name="amd_type" style="width: 100%;" required>
                <option value="">Select a type...</option>
                <option value="condo">condo</option>
                <option value="dormitory">dormitory</option>
                <option value="apartment">apartment</option>
                <option value="hotel">hotel</option>
                <option value="Mansion">Mansion</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="amd_address" class="form-label">Address<span class="text-danger">*</span></label>
            <textarea class="form-control" id="amd_address" name="amd_address" rows="5" cols="50" required></textarea>
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
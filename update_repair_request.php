<?php
include_once("sys_header.inc.php");
ob_start();
?>
<?php
include_once 'class/Database.php';
include_once 'class/RepairRequest.php';

$database = new Database();
$db = $database->getConnection();

$request = new RepairRequest($db);

$id = $_GET["id"];
$req = $request->getRepairRequestById($id); // ใช้ getEmployeeById แทน getEmployeesById
$image = $request->getImagesById($id); // ใช้ getEmployeeById แทน getEmployeesById



?>
<div class="container ">
    <h1 class="mb-4">Update Repair Required</h1>
    <form  action="update_handler.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="req_id" value="<?php echo htmlspecialchars($req['req_id']); ?>">
        <div class="mb-3">
            <label for="req_heading" class="form-label">Heading<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="req_heading" name="req_heading" value="<?php echo $req['req_heading'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="req_detail" class="form-label">Detail<span class="text-danger">*</span></label>
            <textarea class="form-control" id="req_detail" name="req_detail" required><?php echo $req['req_detail'] ?></textarea>
        </div>

            <input type="hidden" class="form-control" id="created_by" name="created_by" value="<?php echo $req['created_by'] ?>" required>
            <input type="hidden" class="form-control" id="user_id" name="user_id" value="<?php echo $req['user_id'] ?>" required>
       
            <div class="d-flex gap-4 table-responsive">
            <?php foreach ($image as $img) : ?>
            <div class="mb-3 ">
                <img src="<?php echo $img['img_path'] ?>" alt="" width="100px">
                <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="delete_images[]" value="<?php echo $img['img_id'] ?>">
                        <label class="form-check-label">Delete this image</label>
                    </div>            </div>
        <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label for="images" class="form-label">Image<span class="text-danger">*</span></label>
            <input type="file" class="form-control" id="images" name="images[]" multiple >
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Update</button>
    </form>
</div>


<!-- Ajax แสดง sweetalert -->


<?php
$content = ob_get_clean();
include 'layout.php';
?>
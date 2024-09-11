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
<div class="container pb-3 ">
    <h1 class="mb-4">Detail Repair Required</h1>
    <div class="mb-3">
        <label for="req_heading" class="form-label">Heading</label>
        <input type="text" class="form-control" id="req_heading" name="req_heading" value="<?php echo $req['req_heading'] ?>" >
    </div>
    <div class="mb-3">
        <label for="req_detail" class="form-label">Detail</label>
        <textarea class="form-control" id="req_detail" name="req_detail" required ><?php echo $req['req_detail'] ?></textarea>
    </div>

    <input type="hidden" class="form-control" id="created_by" name="created_by" value="<?php echo $req['created_by'] ?>" required>
    <input type="hidden" class="form-control" id="user_id" name="user_id" value="<?php echo $req['user_id'] ?>" required>

    <div class="d-flex gap-4 table-responsive">
        <?php foreach ($image as $img) : ?>
            <div class="mb-3 ">
                <img src="<?php echo $img['img_path'] ?>" alt="" width="100px">
            </div>
        <?php endforeach; ?>
    </div>

    <div class="">
        <b>Customer Detail</b>
    </div>
    <div class="card mb-3">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><b>Name:</b> <?php echo htmlspecialchars($req['user_name']); ?></li>
            <li class="list-group-item"><b>Phone:</b> <?php echo htmlspecialchars($req['user_phone']); ?></li>
            <li class="list-group-item"><b>Room:</b> <?php echo htmlspecialchars($req['user_room']); ?></li>
            <li class="list-group-item"><b>Address:</b> <?php echo htmlspecialchars($req['amd_name']); ?>  <?php echo htmlspecialchars($req['amd_type']); ?></li>
        </ul>
    </div>
</div>


<!-- Ajax แสดง sweetalert -->


<?php
$content = ob_get_clean();
include 'layout.php';
?>
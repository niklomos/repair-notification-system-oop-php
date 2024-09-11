<?php
include_once("sys_header.inc.php");
ob_start();
?>
<?php
include_once 'class/Database.php';
include_once 'class/RepairRequest.php';
include_once 'class/Employee.php';
include_once 'class/Schedule.php';

if (isset($_SESSION['acc_id'])) {
    $acc_permission = $_SESSION['acc_permission'];
} 
$database = new Database();
$db = $database->getConnection();

$request = new RepairRequest($db);
$employee = new Employee($db);

$id = $_GET["id"];
$req = $request->getRepairRequestConfirmById($id); // ใช้ getEmployeeById แทน getEmployeesById
$image = $request->getImagesById($id); // ใช้ getEmployeeById แทน getEmployeesById
$images = $request->getImageSuccessById($id); // ใช้ getEmployeeById แทน getEmployeesById

if (isset($_SESSION['acc_id'])) {
    $acc_id = $_SESSION['acc_id'];
    $emp = $employee->employeeData($acc_id);
}

if (isset($_POST['update'])) {
    $schedule = new Schedule($db);

    $schedule->sch_date = $_POST['sch_date'];
    $schedule->emp_id =  $_POST['emp_id'];
    $schedule->req_id = $_POST['req_id'];

    $result =  $schedule->insertSchedule();

    if ($result) {
        echo "<script>
    alert('เพิ่มข้อมูลสำเร็จ');
    window.location.href = 'summary_employee.php';
</script>";
        exit;
    }
}

?>
<div class="container pb-3 ">
    <h1 class="mb-4">Completed Repair</h1>
    <input type="hidden" name="req_id" value="<?php echo htmlspecialchars($req['req_id']); ?>">
    <div class="input-group mb-3">
        <a class="text-primary toggle-form"><i class="fa-solid fa-circle-chevron-down me-1"></i></a>
    </div>
    <div class="toggleable ">
        <div class="mb-3">
            <label for="req_heading" class="form-label">Heading</label>
            <input type="text" class="form-control" id="req_heading" name="req_heading" value="<?php echo $req['req_heading'] ?>">
        </div>
        <div class="mb-3">
            <label for="req_detail" class="form-label">Detail</label>
            <textarea class="form-control" id="req_detail" name="req_detail"><?php echo $req['req_detail'] ?></textarea>
        </div>

        <input type="hidden" class="form-control" id="created_by" name="created_by" value="<?php echo $req['created_by'] ?>">
        <input type="hidden" class="form-control" id="user_id" name="user_id" value="<?php echo $req['user_id'] ?>">

        <b>Before: </b>
        <div class="d-flex gap-3 mb-3 table-responsive">
            <?php foreach ($image as $img) : ?>
                <div>
                    <img src="<?php echo $img['img_path'] ?>" alt="" width="100px">
                </div>
            <?php endforeach; ?>
        </div>
        <b>After: </b>
        <div class="d-flex gap-3 mb-3 table-responsive">
            <?php foreach ($images as $imgs) : ?>
                <div>
                    <img src="<?php echo $imgs['imgs_path'] ?>" alt="" width="100px">
                </div>
            <?php endforeach; ?>
        </div>
        <div class="mb-2">
        <b >Employee Detail</b>
        </div>
        <div class="card mb-3">
            <ul class="list-group list-group-flush">
                <li class="list-group-item" ><b>Name:</b><?php echo htmlspecialchars($req['emp_name']); ?></li>
                <li class="list-group-item"><b>Phone: </b><?php echo htmlspecialchars($req['emp_phone']); ?></li>
                <li class="list-group-item"><b>Scheduled:</b> <?php echo htmlspecialchars($req['sch_date']); ?></li>
            </ul>
        </div>
        <?php if($acc_permission == 'a'){?>
            <div class="mb-2">
            <b>Repair Detail</b>
        </div>
        <div class="card mb-3">
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><b>Name:</b> <?php echo htmlspecialchars($req['user_name']); ?></li>
                <li class="list-group-item"><b>Phone:</b> <?php echo htmlspecialchars($req['user_phone']); ?></li>
                <li class="list-group-item"><b>Room:</b> <?php echo htmlspecialchars($req['user_room']); ?></li>
                <li class="list-group-item"><b>Address:</b> <?php echo htmlspecialchars($req['amd_name'] );  ?> <?php echo htmlspecialchars($req['amd_type']);?></li>
                <li class="list-group-item"><b>Created_at:</b> <?php echo htmlspecialchars($req['created_at']); ?></li>
                <li class="list-group-item"><b>Scheduled_at:</b> <?php echo htmlspecialchars($req['sch_date']); ?></li>
            </ul>
        </div>
        <?php }?>
    </div>
</div>


<script>
    $(document).ready(function() {
        $(".toggle-form").click(function() {
            $(".toggleable").toggle(500);
        });
    });
</script>


<?php
$content = ob_get_clean();
include 'layout.php';
?>
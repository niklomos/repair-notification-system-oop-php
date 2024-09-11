<?php
include_once("sys_header.inc.php");
ob_start();
?>

<?php
include_once 'class/database.php';
include_once 'class/RepairRequest.php';
include_once 'class/Employee.php';

$database = new Database();
$db = $database->getConnection();

$employee = new Employee($db);

if (isset($_SESSION['acc_id'])) {

    $acc_id = $_SESSION['acc_id'];
    $emp = $employee->employeeData($acc_id);
    $emp_id =  $emp['emp_id'];
    $req_status =1;

    $request = new RepairRequest($db);
    $request = $request->getRepairRequestScheduleForEmployee($req_status,$emp_id);
}

?>
<div>
    <div class="container ">
        <div class=" mb-4">
            <h1 class="mb-0 text-center">Scheduled</h1>
        </div>

        <?php
        $i = 1;
        ?>
        <div class="table-responsive">
            <?php if (count($request) > 0) { ?>
                <table class="table m-0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Heading</th>
                            <th>Name</th>
                            <th>Schedule_at</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($request as $req) : ?>
                            <tr>
                                <td><?php echo $i++ ?></td>
                                <td><?php echo $req['req_heading']; ?></td>
                                <td><?php echo $req['user_name']; ?></td>
                                <td><?php echo $req['sch_date']; ?></td>
                                <td><a href="update_repair_request_confirm_employee.php?id=<?php echo $req['req_id']; ?>" class="btn bg-purple">Update</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="alert alert-danger" role="alert">
                    No data found
                </div>
            <?php } ?>
        </div>
    </div>

</div>
<?php
$content = ob_get_clean();
include 'layout.php';
?>
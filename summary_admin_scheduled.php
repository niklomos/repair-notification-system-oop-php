<?php
include_once("sys_header.inc.php");
ob_start();
?>

<?php
include_once 'class/database.php';
include_once 'class/RepairRequest.php';
include_once 'class/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if (isset($_SESSION['acc_id'])) {

    $acc_id = $_SESSION['acc_id'];
    $users = $user->userData($acc_id);
    $user_id =  'x';
    $req_status =1;

    $request = new RepairRequest($db);
    $request = $request->getRepairRequestSchedule($req_status,$user_id);
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
                    <thead class="table-dark ">
                        <tr>
                            <th>No.</th>
                            <th>Heading</th>
                            <th>Employee</th>
                            <th>Schedule_at</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($request as $req) : ?>
                            <tr>
                                <td><?php echo $i++ ?></td>
                                <td><?php echo $req['req_heading']; ?></td>
                                <td><?php echo $req['emp_name']; ?></td>
                                <td><?php echo $req['sch_date']; ?></td>
                                <td><a href="select_repair_request_scheduled.php?id=<?php echo $req['req_id']; ?>" class="btn btn-warning">Detail</a></td>
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
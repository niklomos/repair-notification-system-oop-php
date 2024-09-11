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
    $user_id =  $users['user_id'];
    $req_status =0;

    $request = new RepairRequest($db);
    $request = $request->getRepairRequest($req_status,$user_id);
}

?>
<div>
    <div class="container ">
        <h1 class="mb-0 text-center">Summary</h1>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="insert_repair_request.php" class="btn btn-success me-2"><i class="fas fa-plus me-1"></i>Insert</a>
            </div>
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
                            <th>Detail</th>
                            <th>Created_At</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($request as $req) : ?>
                            <tr>
                                <td><?php echo $i++ ?></td>
                                <td><?php echo $req['req_heading']; ?></td>
                                <td><?php echo $req['req_detail']; ?></td>
                                <td><?php echo $req['created_at']; ?></td>
                                <td><a href="update_repair_request.php?id=<?php echo $req['req_id']; ?>" class="btn btn-primary">Edit</a></td>
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
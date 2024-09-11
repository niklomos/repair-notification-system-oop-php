<?php
include_once("sys_header.inc.php");
ob_start();
?>

<?php
include_once 'class/database.php';
include_once 'class/RepairRequest.php';

$database = new Database();
$db = $database->getConnection();

    $user_id =  'x';
    $req_status =0;

    $request = new RepairRequest($db);
    $request = $request->getRepairRequest($req_status,$user_id);

?>
<div>
    <div class="container ">
        <div class=" mb-4">
            <h1 class="mb-0 text-center">Summary</h1>
        </div>

        <?php
        $i = 1;
        ?>
        <div class="table-responsive">
            <?php if (count($request) > 0) { ?>
                <table class="table m-0  ">
                    <thead class="table-dark ">
                        <tr>
                            <th>No.</th>
                            <th>Heading</th>
                            <th>Name</th>
                            <th>Room</th>
                            <th>Address</th>
                            <th>Created_At</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($request as $req) : ?>
                            <tr>
                                <td><?php echo $i++ ?></td>
                                <td><?php echo $req['req_heading']; ?></td>
                                <td><?php echo $req['user_name']; ?></td>
                                <td><?php echo $req['user_room']; ?></td>
                                <td><?php echo $req['amd_name']; ?> <?php echo $req['amd_type']; ?></td>
                                <td><?php echo $req['created_at']; ?></td>
                                <td><a href="select_repair_request.php?id=<?php echo $req['req_id']; ?>" class="btn btn-warning">detail</a></td>
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
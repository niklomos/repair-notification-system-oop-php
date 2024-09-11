<?php
include_once("sys_header.inc.php");
ob_start();
?>

<?php
include_once 'class/database.php';
include_once 'class/Account.php';

$database = new Database();
$db = $database->getConnection();

$account = new Account($db);

$acc_status = 0;
$acc_permission = 'e';
$account = $account->getAccount($acc_status,$acc_permission);
?>
<div>
    <div class="container ">
    <div class=" mb-4">
            <h1 class="mb-0 text-center">Recycle Employee</h1>
        </div>

        <?php
        $i = 1;
        ?>
        <div class="table-responsive">
            <?php if (count($account) > 0) { ?>
                <table class="table m-0">
                    <thead class="table-dark ">
                        <tr>
                            <th>No.</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Created_At</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($account as $acc) : ?>
                            <tr>
                                <td><?php echo $i++ ?></td>
                                <td><?php echo $acc['username']; ?></td>                                
                                <td><?php echo $acc['emp_name']; ?></td>                                
                                <td><?php echo $acc['created_at']; ?></td>                                
                                <td><a href="update_account_employees_inactive.php?id=<?php echo $acc['acc_id']; ?>" class="btn btn-danger">Edit</a></td>
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
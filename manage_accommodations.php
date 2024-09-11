<?php
include_once("sys_header.inc.php");
ob_start();
?>

<?php
include_once 'class/database.php';
include_once 'class/Accommodation.php';

$database = new Database();
$db = $database->getConnection();

$accommodation = new Accommodation($db);

$amd_status = 1;
$accommodation = $accommodation->getAccommodation($amd_status);
?>
<div>
    <div class="container ">
        <h1 class="mb-0 text-center">Accommodations</h1>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="insert_accommodation.php" class="btn btn-success me-2"><i class="fas fa-plus me-1"></i>Insert</a>
                <a href="recycle_accommodations.php" class="btn btn-danger"><i class="fas fa-trash-alt me-1"></i>Recycle</a>
            </div>
        </div>

        <?php
        $i = 1;
        ?>
        <div class="table-responsive">
            <?php if (count($accommodation) > 0) { ?>
                <table class="table m-0">
                    <thead class="table-dark ">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Created_At</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($accommodation as $amd) : ?>
                            <tr>
                                <td><?php echo $i++ ?></td>
                                <td><?php echo $amd['amd_name']; ?></td>                                
                                <td><?php echo $amd['amd_type']; ?></td>                                
                                <td><?php echo $amd['created_at']; ?></td>                                
                                <td><a href="update_accommodation.php?id=<?php echo $amd['amd_id']; ?>" class="btn btn-warning">Edit</a></td>
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
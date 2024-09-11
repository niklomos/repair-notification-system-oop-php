<?php
include_once("sys_header.inc.php");
ob_start();
?>

<?php
include_once 'class/Database.php';
include_once 'class/Accommodation.php';

$database = new Database();
$db = $database->getConnection();

$accommodation = new Accommodation($db);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $amd = $accommodation->getAccommodationById($id);
}
?>

<div class="container pb-3">
    <h1 class="mb-4">Update Accommodation</h1>
    <form id="updateForm">
        <input type="hidden" name="amd_id" value="<?php echo htmlspecialchars($amd['amd_id']); ?>">
        <div class="mb-3">
            <label for="amd_name" class="form-label">Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="amd_name" name="amd_name" value="<?php echo htmlspecialchars($amd['amd_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="amd_type" class="form-label">Type<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="amd_type" name="amd_type" value="<?php echo htmlspecialchars($amd['amd_type']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="amd_address" class="form-label">Address<span class="text-danger">*</span></label>
            <textarea class="form-control" id="amd_address" name="amd_address" rows="5" required><?php echo htmlspecialchars($amd['amd_address']); ?></textarea>
        </div>
        <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Update</button>
        <a href="#" class="btn btn-danger b-button" id="inactiveButton"><i class="fas fa-trash-alt me-1"></i>Inactive</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#updateForm').submit(function(e) {
        e.preventDefault();

     
        $.ajax({
            url: 'update-test.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                console.log("AJAX request succeeded"); // Debugging line
                console.log(response); // Check the response from server

                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'manage_accommodations.php';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log("AJAX request failed"); // Debugging line
                console.log(xhr.responseText); // Print out the response text in case of error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    $('#inactiveButton').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this accommodation?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'update-test.php',
                    type: 'GET',
                    data: { inactive_id: '<?php echo htmlspecialchars($amd['amd_id']); ?>' },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            window.location.href = 'manage_accommodations.php';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An unexpected error occurred.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
});
</script>
<?php
$content = ob_get_clean();
include 'layout.php';
?>

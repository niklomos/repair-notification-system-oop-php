<?php
include_once("sys_header.inc.php");
ob_start();
?>

<?php
include_once 'class/Database.php';
include_once 'class/User.php';

$database = new Database();
$db = $database->getConnection();

$users = new User($db);
$id = $_GET["id"];
$user = $users->getUserById($id);
?>

<div class="container">
    <h1 class="mb-4">Profile</h1>
    <form id="profileForm" method="post">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
        <div class="mb-3">
            <label for="user_name" class="form-label">Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user['user_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="user_phone" class="form-label">Phone<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="user_phone" name="user_phone" value="<?php echo htmlspecialchars($user['user_phone']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="user_room" class="form-label">Room<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="user_room" name="user_room" value="<?php echo htmlspecialchars($user['user_room']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="amd_id" class="form-label">Address<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="amd_id" name="amd_id" value="<?php echo htmlspecialchars($user['amd_name']); ?>" disabled>
        </div>
        <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Update</button>
    </form>
</div>

<!-- Ajax แสดง sweetalert -->
<script>
    $(document).ready(function() {
        $('#profileForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: 'update_profile.php', // Update with your actual PHP script path
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message
                        }).then(() => {
                            window.location.href = 'summary_user.php'; // Redirect to manage_employees.php
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while processing your request.'
                    });
                }
            });
        });
    });
</script>

<?php
$content = ob_get_clean();
include 'layout.php';

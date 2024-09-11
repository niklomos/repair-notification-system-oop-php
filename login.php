<?php
require("sys_header.inc.php");
?>

<?php
session_start();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="hold-transition login-page bg-dark ">
    <div class="login-box">
        <div class="">
            <div class="text-center text-primary">
                <a href="" class="h3"><b> Repair Notification System</b></a>
            </div>
            <div class="card-body text-light">
                <form action="login_check.php" method="POST">
                    <div class="input-group py-2">
                        <input id="username" name="username" type="text" class="form-control" placeholder="Enter your username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group py-2">
                        <input id="Password" name="password" type="password" class="form-control" placeholder="Enter your password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col-8">
                            <div class="icheck-primary text-light">
                                <input type="checkbox" id="remember" name="RememberMe">
                                <label for="remember">Remember Me</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
        

    <?php
    if (isset($_SESSION['error'])) {
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "Login Failed",
                text: "' . $_SESSION['error'] . '",
                confirmButtonText: "OK"
            });
        </script>';
        unset($_SESSION['error']);
    }
    ?>
</body>
</html>

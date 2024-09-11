<?php
session_start(); // เริ่มต้น session

include_once("class/Database.php");
include_once("class/Account.php");

$connectDB = new Database;
$db = $connectDB->getConnection();

$account = new Account($db);

if (isset($_SESSION['acc_id'])) {
    $acc_id = $_SESSION['acc_id'];
    $username = $account->accountData($acc_id);
    $acc_permission = $_SESSION['acc_permission'];
} else {
    header('Location: login.php'); // ทำการเปลี่ยนเส้นทางไปยัง manage_employees.php
    exit(); // หยุดการประมวลผลของสคริปต์
}
?>
<?php

function isSubMenuOpen($pages)
{
    foreach ($pages as $page) {
        if (basename($_SERVER['PHP_SELF']) === $page) {
            return 'menu-open';
        }
    }
    return '';
}
function isMenuItemActive($pages)
{
    foreach ($pages as $page) {
        if (basename($_SERVER['PHP_SELF']) === $page) {
            return 'active';
        }
    }
    return '';
}

function PagesOpenAndActive()
{
    return [
        'summary_admin.php',
        'summary_admin_scheduled.php',
        'summary_admin_complete.php',
        'select_repair_request.php',
        'select_repair_request_scheduled.php',
        'repair_request_success_user.php'
    ];
}
function PagesAdmin()
{
    return [
        'summary_admin.php',
        'select_repair_request.php'
    ];
}
function PagesScheduledAdmin()
{
    return [
        'summary_admin_scheduled.php',
        'select_repair_request_scheduled.php'
    ];
}
function PagesCompletedAdmin()
{
    return [
        'summary_admin_complete.php',
        'repair_request_success_user.php'
    ];
}

function PagesSummaryUser()
{
    return [
        'summary_user.php',
        'insert_repair_request.php',
        'update_repair_request.php',

    ];
}
function PagesSummaryEmp()
{
    return [
        'summary_employee.php',
        'update_schedule.php',

    ];
}
function PagesScheduledUser()
{
    return [
        'summary_user_scheduled.php',
        'select_repair_request_scheduled.php',

    ];
}
function PagesScheduledEmp()
{
    return [
        'summary_employee_scheduled.php',
        'update_repair_request_confirm_employee.php',

    ];
}
function PagesCompletedUser()
{
    return [
        'summary_user_complete.php',
        'repair_request_success_user.php',

    ];
}
function PagesCompletedEmp()
{
    return [
        'summary_employee_complete.php',
        'repair_request_success_employee.php',

    ];
}
function PagesProfileUser()
{
    return [
        'profile_user.php',
    ];
}
function PagesProfileEmp()
{
    return [
        'profile_employee.php',
    ];
}
function PagesAccountUsers()
{
    return [
        'manage_account_users.php',
        'insert_account_users.php',
        'update_account_users.php',
        'recycle_account_users.php',
        'update_account_users_inactive.php'

    ];
}
function PagesAccountEmployees()
{
    return [
        'manage_account_employees.php',
        'insert_account_employees.php',
        'update_account_employees.php',
        'recycle_account_employees.php',
        'update_account_employees_inactive.php'

    ];
}
function PagesAccommodation()
{
    return [
        'manage_accommodations.php',
        'insert_accommodation.php',
        'update_accommodation.php',
        'recycle_accommodations.php',
        'update_accommodation_inactive.php'

    ];
}


?>

<head>
    <title>Home</title>
</head>

<body>

    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand  navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto me-2">
                <!-- <li></li> -->
                <a class="btn <?php echo $acc_permission == 'e' ? 'bg-purple' : ($acc_permission == 'u' ? 'btn-primary' : 'btn-warning'); ?> " href="logout.php">Logout</a>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-<?php echo $acc_permission == 'e' ? 'purple' : ($acc_permission == 'u' ? 'primary' : 'warning'); ?> elevation-4">

            <!-- Brand Logo -->
            <a href="index.php" class="brand-link">
                <!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
                <span class="brand-text font-weight-warning  "><b>Repair Notification System</b></span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="dist/img/<?php echo $acc_permission == 'e' ? 'avatar5.png' : ($acc_permission == 'u' ? 'avatar4.png' : 'avatar3.png'); ?>" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?php echo $username['username'] ?></a>
                    </div>
                </div>


                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <?php if ($acc_permission == 'e') { ?>
                            <li class="nav-item">
                                <a href="summary_employee.php" class="nav-link <?php echo isMenuItemActive(PagesSummaryEmp()); ?> ">
                                    <i class="nav-icon fas fa-chart-line ml-1"></i>
                                    <p class="ml-2">Summary</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="summary_employee_scheduled.php" class="nav-link <?php echo isMenuItemActive(PagesScheduledEmp()); ?> ">
                                    <i class="nav-icon fas fa-calendar-alt ml-1"></i>
                                    <p class="ml-2"> Scheduled Repairs </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="summary_employee_complete.php" class="nav-link <?php echo isMenuItemActive(PagesCompletedEmp()); ?> ">
                                    <i class="nav-icon fas fa-check-square ml-1"></i>
                                    <p class="ml-2"> Completed Repairs </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="profile_employee.php?id=<?php echo $acc_id ?>" class="nav-link <?php echo isMenuItemActive(PagesProfileEmp()); ?> ">
                                    <i class="nav-icon fas fa-id-badge ml-1"></i>
                                    <p class="ml-2"> Profile </p>
                                </a>
                            </li>
                        <?php } elseif ($acc_permission == 'u') { ?>
                            <li class="nav-item">
                                <a href="summary_user.php" class="nav-link <?php echo isMenuItemActive(PagesSummaryUser()); ?> ">
                                    <i class="nav-icon fas fa-chart-line ml-1"></i>
                                    <p class="ml-2"> Summary </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="summary_user_scheduled.php" class="nav-link <?php echo isMenuItemActive(PagesScheduledUser()); ?> ">
                                    <i class="nav-icon fas fa-calendar-alt ml-1"></i>
                                    <p class="ml-2"> Scheduled Repair </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="summary_user_complete.php" class="nav-link <?php echo isMenuItemActive(PagesCompletedUser()); ?> ">
                                    <i class="nav-icon fas fa-check-square ml-1"></i>
                                    <p class="ml-2"> Completed Repairs </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="profile_user.php?id=<?php echo $acc_id ?>" class="nav-link <?php echo isMenuItemActive(PagesProfileUser()); ?> ">
                                    <i class="nav-icon fas fa-id-badge ml-1"></i>
                                    <p class="ml-2"> Profile </p>
                                </a>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item ml-1 <?php echo isSubMenuOpen(PagesOpenAndActive());?> ">
                                <a href="#" class="nav-link <?php echo isMenuItemActive(PagesOpenAndActive());?> ">
                                    <i class="nav-icon fas fa-chart-bar"></i>
                                    <p>
                                        Summary
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview ">
                                    <li class="nav-item">
                                        <a href="summary_admin.php" class="nav-link <?php echo isMenuItemActive(PagesAdmin()); ?> ">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p >Repair Request</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="summary_admin_scheduled.php" class="nav-link <?php echo isMenuItemActive(PagesScheduledAdmin()); ?> ">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Scheduled Repair</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="summary_admin_complete.php" class="nav-link  <?php echo isMenuItemActive(PagesCompletedAdmin()); ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Completed Repair</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="manage_accommodations.php" class="nav-link <?php echo isMenuItemActive(PagesAccommodation()); ?> ">
                                    <i class="nav-icon fas fa-hotel ml-1"></i>
                                    <p class="ml-2"> Accommodations </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="manage_account_users.php" class="nav-link <?php echo isMenuItemActive(PagesAccountUsers()); ?> ">
                                    <i class="nav-icon fas fa-users ml-1"></i>
                                    <p class="ml-2"> Account-Users </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="manage_account_employees.php" class="nav-link <?php echo isMenuItemActive(PagesAccountEmployees()); ?> ">
                                    <i class="nav-icon fas fa-user-tie ml-1"></i>
                                    <p class="ml-2"> Account-Employee </p>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <div class="content-wrapper">

            <main>
                <?php echo $content; ?>
            </main>

        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                V.1
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2024 Repair Notification System .</strong> All rights reserved.
        </footer>

    </div>
    <!-- ./wrapper -->

    

</body>

</html>
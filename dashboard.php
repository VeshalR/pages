<?php
include '../includes/connection.php';
include '../includes/topp.php';


// Sample cart summary
$totalItems = 0;
$totalSales = 0.0;

if (!empty($_SESSION['pointofsale'])) {
    foreach ($_SESSION['pointofsale'] as $product) {
        $totalItems += $product['quantity'];
        $totalSales += $product['price'] * $product['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - KCC Secure Stock</title>
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" style="min-height: 100vh;">

        <!-- Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-laptop"></i>
            </div>
            <div class="sidebar-brand-text mx-3">KCC Inventory</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Navigation Items -->
        <li class="nav-item active">
            <a class="nav-link" href="dashboard.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="pos.php">
                <i class="fas fa-fw fa-cash-register"></i>
                <span>Point of Sale</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="inventory.php">
                <i class="fas fa-fw fa-boxes"></i>
                <span>Inventory</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="repair_tracking.php">
                <i class="fas fa-tools"></i>
                <span>Repair Tracking</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="appointments.php">
                <i class="fas fa-calendar-check"></i>
                <span>Appointments</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="staff_admin.php">
                <i class="fas fa-user-shield"></i>
                <span>Admin & Staff</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Logout -->
        <li class="nav-item">
            <a class="nav-link" href="../logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span></a>
        </li>

    </ul>
    <!-- End Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <h1 class="h3 text-gray-800">Dashboard</h1>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid">
                <div class="row">

                    <!-- Total Items -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Items in Cart</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalItems; ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Sales -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Cart Value</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">₱ <?php echo number_format($totalSales, 2); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Shortcut to POS -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <a href="pos.php" class="text-decoration-none">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Point of Sale</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Go to POS</div>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>

                <!-- Add more widgets or recent activity table here -->

            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="text-center my-auto">
                    <span>KCC Secure Stock &copy; <?php echo date("Y"); ?></span>
                </div>
            </div>
        </footer>

    </div>
</div>

<!-- Scripts -->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>

</body>
</html>

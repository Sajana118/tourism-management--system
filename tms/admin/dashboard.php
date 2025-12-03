<?php
session_start();
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {	
    header('location:index.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | TMS</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Custom CSS -->
<link href="../assets/css/pages/admin-dashboard.css" rel="stylesheet">
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-header">
        <div style="font-size: 2.5rem;"><i class="fas fa-mountain"></i></div>
        <h4>TMS</h4>
        <p>Admin TMS</p>
    </div>
    <ul class="sidebar-menu">
        <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="manage-users.php"><i class="fas fa-users"></i> Manage Users</a></li>
        <li><a href="manage-packages.php"><i class="fas fa-box"></i> Tour Packages</a></li>
        <li><a href="create-package.php"><i class="fas fa-plus-circle"></i> Create Package</a></li>
        <li><a href="manage-bookings.php"><i class="fas fa-calendar-check"></i> Bookings</a></li>
        <li><a href="manage-payments.php"><i class="fas fa-credit-card"></i> Payment Management</a></li>
        <li><a href="profile.php"><i class="fas fa-user-circle"></i> My Profile</a></li>
        <li><a href="change-password.php"><i class="fas fa-key"></i> Change Password</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</aside>

<!-- Main Content -->
<main class="main-content">
    <!-- Top Bar -->
    <div class="topbar">
        <div class="topbar-left">
            <h5><i class="fas fa-chart-line me-2"></i>Dashboard Overview</h5>
        </div>
        <div class="topbar-right">
            <div class="user-profile">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['alogin'], 0, 1)); ?>
                </div>
                <div>
                    <strong><?php echo htmlentities($_SESSION['alogin']); ?></strong>
                    <small class="d-block text-muted">Administrator</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard-content">
        <!-- Stat Cards Row 1 -->
        <div class="row g-4 mb-4">
            <!-- Users -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card users">
                    <div class="stat-icon blue">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">
                        <?php 
                        $sql = "SELECT COUNT(*) as total FROM tblusers";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        echo $result->total;
                        ?>
                    </div>
                    <div class="stat-label">Registered Users</div>
                    <a href="manage-users.php" class="btn btn-sm btn-outline-primary mt-3">View Details</a>
                </div>
            </div>

            <!-- Packages -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card packages">
                    <div class="stat-icon green">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-number">
                        <?php 
                        $sql = "SELECT COUNT(*) as total FROM tbltourpackages";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        echo $result->total;
                        ?>
                    </div>
                    <div class="stat-label">Tour Packages</div>
                    <a href="manage-packages.php" class="btn btn-sm btn-outline-success mt-3">Manage Packages</a>
                </div>
            </div>

            <!-- Total Bookings -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card bookings">
                    <div class="stat-icon red">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-number">
                        <?php 
                        $sql = "SELECT COUNT(*) as total FROM tblbooking";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        echo $result->total;
                        ?>
                    </div>
                    <div class="stat-label">Total Bookings</div>
                    <a href="manage-bookings.php" class="btn btn-sm btn-outline-danger mt-3">View Bookings</a>
                </div>
            </div>

            <!-- Total Payments -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card enquiries">
                    <div class="stat-icon orange">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="stat-number">
                        <?php 
                        $sql = "SELECT COUNT(*) as total FROM tblpayment";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        echo $result->total;
                        ?>
                    </div>
                    <div class="stat-label">Total Payments</div>
                    <a href="manage-payments.php" class="btn btn-sm btn-outline-warning mt-3">View Payments</a>
                </div>
            </div>
        </div>

        <!-- Stat Cards Row 2 -->
        <div class="row g-4 mb-4">
            <!-- Pending Bookings -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card" style="border-left-color: #8b5cf6;">
                    <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-number">
                        <?php 
                        $sql = "SELECT COUNT(*) as total FROM tblbooking WHERE status IS NULL OR status=''";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        echo $result->total;
                        ?>
                    </div>
                    <div class="stat-label">Pending Bookings</div>
                </div>
            </div>

            <!-- Confirmed Bookings -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card" style="border-left-color: #06b6d4;">
                    <div class="stat-icon" style="background: rgba(6, 182, 212, 0.1); color: #06b6d4;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-number">
                        <?php 
                        $sql = "SELECT COUNT(*) as total FROM tblbooking WHERE status='1'";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        echo $result->total;
                        ?>
                    </div>
                    <div class="stat-label">Confirmed Bookings</div>
                </div>
            </div>

            <!-- Cancelled Bookings -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card" style="border-left-color: #ef4444;">
                    <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-number">
                        <?php 
                        $sql = "SELECT COUNT(*) as total FROM tblbooking WHERE status='2'";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        echo $result->total;
                        ?>
                    </div>
                    <div class="stat-label">Cancelled Bookings</div>
                </div>
            </div>

            <!-- Completed Payments -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card" style="border-left-color: #10b981;">
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-number">
                        <?php 
                        $sql = "SELECT COUNT(*) as total FROM tblpayment WHERE PaymentStatus='completed'";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        echo $result->total;
                        ?>
                    </div>
                    <div class="stat-label">Completed Payments</div>
                </div>
            </div>
        </div>

        <!-- Data elements for charts -->
        <div id="bookings-chart-data" data-bookings="[
            <?php 
            $sql = 'SELECT COUNT(*) as total FROM tblbooking WHERE status IS NULL OR status=\'\'';
            $query = $dbh->prepare($sql);
            $query->execute();
            echo $query->fetch(PDO::FETCH_OBJ)->total;
            ?>,
            <?php 
            $sql = 'SELECT COUNT(*) as total FROM tblbooking WHERE status=\'1\'';
            $query = $dbh->prepare($sql);
            $query->execute();
            echo $query->fetch(PDO::FETCH_OBJ)->total;
            ?>,
            <?php 
            $sql = 'SELECT COUNT(*) as total FROM tblbooking WHERE status=\'2\'';
            $query = $dbh->prepare($sql);
            $query->execute();
            echo $query->fetch(PDO::FETCH_OBJ)->total;
            ?>
        ]" style="display: none;"></div>
        <div id="packages-chart-data" data-packages="[
            <?php 
            $sql = 'SELECT COUNT(*) as total FROM tbltourpackages';
            $query = $dbh->prepare($sql);
            $query->execute();
            echo $query->fetch(PDO::FETCH_OBJ)->total;
            ?>,
            <?php 
            $sql = 'SELECT COUNT(*) as total FROM tblbooking';
            $query = $dbh->prepare($sql);
            $query->execute();
            echo $query->fetch(PDO::FETCH_OBJ)->total;
            ?>
        ]" style="display: none;"></div>

        <!-- Charts -->
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="chart-card">
                    <h5><i class="fas fa-chart-bar me-2"></i>Bookings Overview</h5>
                    <canvas id="bookingsChart"></canvas>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="chart-card">
                    <h5><i class="fas fa-chart-pie me-2"></i>Package Distribution</h5>
                    <canvas id="packagesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Chart initialization handled by external JS file -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="../js/pages/admin-dashboard-new.js"></script>
</body>
</html>
<?php } ?>

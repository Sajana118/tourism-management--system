<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}
else{ 
	?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users | TMS Admin</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Custom CSS -->
<link href="../assets/css/pages/admin-dashboard.css" rel="stylesheet">
<link href="../assets/css/pages/admin/manage-users.css" rel="stylesheet">
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
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="manage-users.php" class="active"><i class="fas fa-users"></i> Manage Users</a></li>
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
            <h5><i class="fas fa-users me-2"></i>Manage Users</h5>
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

    <!-- Content -->
    <div class="content">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage Users</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-users me-2"></i>Registered Users</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Mobile No.</th>
                            <th>Email Id</th>
                            <th>RegDate</th>
                            <th>Updation Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sql = "SELECT * from tblusers";
                        $query = $dbh -> prepare($sql);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                        $cnt=1;
                        if($query->rowCount() > 0)
                        {
                        foreach($results as $result)
                        {				
                        ?>
                        <tr>
                            <td><?php echo htmlentities($cnt);?></td>
                            <td><?php echo htmlentities($result->FullName);?></td>
                            <td><?php echo htmlentities($result->MobileNumber);?></td>
                            <td><?php echo htmlentities($result->EmailId);?></td>
                            <td><?php echo htmlentities($result->RegDate);?></td>
                            <td><?php echo htmlentities($result->UpdationDate);?></td>
                            <td>
                                <a href="user-bookings.php?uid=<?php echo htmlentities($result->EmailId);?>&&uname=<?php echo htmlentities($result->FullName);?>" class="btn btn-primary">
                                    <i class="fas fa-calendar-check me-1"></i>User Bookings
                                </a>
                            </td>
                        </tr>
                        <?php $cnt=$cnt+1;} }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>
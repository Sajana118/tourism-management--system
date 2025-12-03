<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}
else{
	// Code for cancel
if($_GET['action']=='cancel' && $_GET['bkid'])
{
$bkid=intval($_GET['bkid']);
$status=2;
$cancelby='a';
$sql = "UPDATE tblbooking SET status=:status,CancelledBy=:cancelby WHERE BookingId=:bkid";
$query = $dbh->prepare($sql);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query -> bindParam(':cancelby',$cancelby , PDO::PARAM_STR);
$query-> bindParam(':bkid',$bkid, PDO::PARAM_STR);
$query -> execute();
$msg="Booking Cancelled successfully";
}

//code for confirm
if($_GET['action']=='confirm' && $_GET['bckid'])
{
$bckid=intval($_GET['bckid']);
$status=1;
$sql = "UPDATE tblbooking SET status=:status WHERE BookingId=:bckid";
$query = $dbh->prepare($sql);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query-> bindParam(':bckid',$bckid, PDO::PARAM_STR);
$query -> execute();
$msg="Booking Confirm successfully";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Bookings | TMS Admin</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Custom CSS -->
<link href="../assets/css/pages/admin-dashboard.css" rel="stylesheet">
<link href="../assets/css/pages/admin-user-bookings.css" rel="stylesheet">
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
        <li><a href="manage-users.php"><i class="fas fa-users"></i> Manage Users</a></li>
        <li><a href="manage-packages.php"><i class="fas fa-box"></i> Tour Packages</a></li>
        <li><a href="create-package.php"><i class="fas fa-plus-circle"></i> Create Package</a></li>
        <li><a href="manage-bookings.php" class="active"><i class="fas fa-calendar-check"></i> Bookings</a></li>
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
            <h5><i class="fas fa-calendar-check me-2"></i>User Bookings</h5>
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
                <li class="breadcrumb-item"><a href="manage-users.php">Manage Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">User Bookings</li>
            </ol>
        </nav>

        <!-- Alert Messages -->
        <?php if($error){?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i><strong>ERROR:</strong> <?php echo htmlentities($error); ?>
            </div>
        <?php } else if($msg){?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i><strong>SUCCESS:</strong> <?php echo htmlentities($msg); ?>
            </div>
        <?php }?>

        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-calendar-check me-2"></i>Manage <?php echo $_GET['uname'];?>'s Bookings</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>User Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Package</th>
                            <th>Dates</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $uid=$_GET['uid'];
                        $sql = "SELECT tblbooking.BookingId as bookid,tblusers.FullName as fname,tblusers.MobileNumber as mnumber,tblusers.EmailId as email,tbltourpackages.PackageName as pckname,tblbooking.PackageId as pid,tblbooking.FromDate as fdate,tblbooking.ToDate as tdate,tblbooking.Comment as comment,tblbooking.status as status,tblbooking.CancelledBy as cancelby,tblbooking.UpdationDate as upddate,tblbooking.Persons,tblbooking.Guide,tblbooking.Vehicle,tblbooking.PaymentAmount,tblbooking.PaymentStatus from  tblbooking
                         left join tblusers  on  tblbooking.UserEmail=tblusers.EmailId
                         left join tbltourpackages on tbltourpackages.PackageId=tblbooking.PackageId where tblbooking.UserEmail='$uid'";
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
                            <td>#BK-<?php echo htmlentities($result->bookid);?></td>
                            <td><?php echo htmlentities($result->fname);?></td>
                            <td><?php echo htmlentities($result->mnumber);?></td>
                            <td><?php echo htmlentities($result->email);?></td>
                            <td><a href="update-package.php?pid=<?php echo htmlentities($result->pid);?>" class="text-decoration-none"><?php echo htmlentities($result->pckname);?></a></td>
                            <td><?php echo htmlentities($result->fdate);?> to <?php echo htmlentities($result->tdate);?></td>
                            <td><?php echo htmlentities($result->comment);?></td>
                            <td>
                                <?php 
                                if($result->status==0) {
                                    echo '<span class="status-badge status-pending">Pending</span>';
                                } elseif($result->status==1) {
                                    echo '<span class="status-badge status-confirmed">Confirmed</span>';
                                } elseif($result->status==2 and $result->cancelby=='a') {
                                    echo '<span class="status-badge status-cancelled">Canceled by you at ' .$result->upddate.'</span>';
                                } elseif($result->status==2 and $result->cancelby=='u') {
                                    echo '<span class="status-badge status-cancelled">Canceled by User at ' .$result->upddate.'</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php if($result->status==2) { ?>
                                    <span class="status-badge status-cancelled">Cancelled</span>
                                <?php } elseif($result->status==1) { ?>
                                    <span class="status-badge status-confirmed">Confirmed</span>
                                <?php } else { ?>
                                    <a href="user-bookings.php?uid=<?php echo $_GET['uid'];?>&uname=<?php echo $_GET['uname'];?>&action=cancel&bkid=<?php echo htmlentities($result->bookid);?>" onclick="return confirm('Do you really want to cancel booking')" class="btn btn-danger btn-sm">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </a>
                                    <a href="user-bookings.php?uid=<?php echo $_GET['uid'];?>&uname=<?php echo $_GET['uname'];?>&action=confirm&bckid=<?php echo htmlentities($result->bookid);?>" onclick="return confirm('Booking has been confirmed')" class="btn btn-success btn-sm">
                                        <i class="fas fa-check me-1"></i>Confirm
                                    </a>
                                <?php } ?>
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
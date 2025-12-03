<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}
else{ 
	// Code for refund payment
	if(isset($_REQUEST['refund']))
	{
		$paymentId=intval($_GET['refund']);
		$status='refunded';
		$sql = "UPDATE tblpayment SET PaymentStatus=:status WHERE PaymentId=:paymentId";
		$query = $dbh->prepare($sql);
		$query -> bindParam(':status',$status, PDO::PARAM_STR);
		$query-> bindParam(':paymentId',$paymentId, PDO::PARAM_STR);
		$query -> execute();
		
		$msg="Payment refunded successfully";
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment Management | TMS</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Custom CSS -->
<link href="../assets/css/pages/admin-dashboard.css" rel="stylesheet">
<link href="../assets/css/pages/admin/manage-payments.css" rel="stylesheet">
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
        <li><a href="manage-bookings.php"><i class="fas fa-calendar-check"></i> Bookings</a></li>
        <li><a href="manage-payments.php" class="active"><i class="fas fa-credit-card"></i> Payment Management</a></li>
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
            <h5><i class="fas fa-credit-card me-2"></i>Payment Management</h5>
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
                <li class="breadcrumb-item active" aria-current="page">Payment Management</li>
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
                <h5><i class="fas fa-credit-card me-2"></i>Payment Transactions</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Booking ID</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Transaction ID</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sql = "SELECT p.PaymentId, p.BookingId, p.Amount, p.PaymentMethod, p.TransactionId, p.PaymentStatus, p.PaymentDate, u.FullName, b.PackageId, tp.PackageName 
                                FROM tblpayment p
                                LEFT JOIN tblusers u ON p.UserId = u.id
                                LEFT JOIN tblbooking b ON p.BookingId = b.BookingId
                                LEFT JOIN tbltourpackages tp ON b.PackageId = tp.PackageId
                                ORDER BY p.PaymentDate DESC";
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
                            <td>#PAY-<?php echo htmlentities($result->PaymentId);?></td>
                            <td>#BK-<?php echo htmlentities($result->BookingId);?></td>
                            <td><?php echo htmlentities($result->FullName);?></td>
                            <td class="amount-highlight">NPR <?php echo number_format(htmlentities($result->Amount), 2);?></td>
                            <td>
                                <span class="payment-method">
                                    <?php 
                                    if($result->PaymentMethod == 'esewa') {
                                        echo '<i class="fas fa-wallet me-1"></i>eSewa';
                                    } else {
                                        echo htmlentities($result->PaymentMethod);
                                    }
                                    ?>
                                </span>
                            </td>
                            <td><?php echo htmlentities($result->TransactionId);?></td>
                            <td><?php echo date('M j, Y', strtotime(htmlentities($result->PaymentDate)));?></td>
                            <td>
                                <?php 
                                $status = $result->PaymentStatus;
                                if($status=='completed') {
                                    echo '<span class="status-badge status-completed">Completed</span>';
                                } elseif($status=='pending') {
                                    echo '<span class="status-badge status-pending">Pending</span>';
                                } elseif($status=='failed') {
                                    echo '<span class="status-badge status-failed">Failed</span>';
                                } elseif($status=='refunded') {
                                    echo '<span class="status-badge status-refunded">Refunded</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php if($status=='completed') { ?>
                                    <a href="manage-payments.php?refund=<?php echo htmlentities($result->PaymentId);?>" onclick="return confirm('Do you really want to refund this payment?')" class="btn btn-warning btn-sm btn-action">
                                        <i class="fas fa-undo me-1"></i>Refund
                                    </a>
                                <?php } elseif($status=='refunded') { ?>
                                    <span class="status-badge status-refunded">Already Refunded</span>
                                <?php } else { ?>
                                    <span class="status-badge status-pending">No Action</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php $cnt=$cnt+1;} }?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Summary Cards -->
        <div class="row mt-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="stat-icon bg-success text-white rounded-circle d-inline-block p-3 mb-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h5 class="card-title">
                            <?php 
                            $sql_total = "SELECT SUM(Amount) as total FROM tblpayment WHERE PaymentStatus='completed'";
                            $query_total = $dbh->prepare($sql_total);
                            $query_total->execute();
                            $result_total = $query_total->fetch(PDO::FETCH_OBJ);
                            echo 'NPR ' . number_format($result_total->total ? $result_total->total : 0, 2);
                            ?>
                        </h5>
                        <p class="card-text">Total Revenue</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="stat-icon bg-info text-white rounded-circle d-inline-block p-3 mb-3">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <h5 class="card-title">
                            <?php 
                            $sql_pending = "SELECT COUNT(*) as count FROM tblpayment WHERE PaymentStatus='pending'";
                            $query_pending = $dbh->prepare($sql_pending);
                            $query_pending->execute();
                            $result_pending = $query_pending->fetch(PDO::FETCH_OBJ);
                            echo $result_pending->count;
                            ?>
                        </h5>
                        <p class="card-text">Pending Payments</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="stat-icon bg-warning text-white rounded-circle d-inline-block p-3 mb-3">
                            <i class="fas fa-undo"></i>
                        </div>
                        <h5 class="card-title">
                            <?php 
                            $sql_refunded = "SELECT COUNT(*) as count FROM tblpayment WHERE PaymentStatus='refunded'";
                            $query_refunded = $dbh->prepare($sql_refunded);
                            $query_refunded->execute();
                            $result_refunded = $query_refunded->fetch(PDO::FETCH_OBJ);
                            echo $result_refunded->count;
                            ?>
                        </h5>
                        <p class="card-text">Refunded Payments</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="stat-icon bg-danger text-white rounded-circle d-inline-block p-3 mb-3">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <h5 class="card-title">
                            <?php 
                            $sql_failed = "SELECT COUNT(*) as count FROM tblpayment WHERE PaymentStatus='failed'";
                            $query_failed = $dbh->prepare($sql_failed);
                            $query_failed->execute();
                            $result_failed = $query_failed->fetch(PDO::FETCH_OBJ);
                            echo $result_failed->count;
                            ?>
                        </h5>
                        <p class="card-text">Failed Payments</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>
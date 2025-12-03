<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
	{	
header('location:index.php');
}
else{
if(isset($_REQUEST['bkid']))
	{
		$bid=intval($_GET['bkid']);
$email=$_SESSION['login'];
	$sql ="SELECT FromDate, PaymentStatus FROM tblbooking WHERE UserEmail=:email and BookingId=:bid";
$query= $dbh -> prepare($sql);
$query-> bindParam(':email', $email, PDO::PARAM_STR);
$query-> bindParam(':bid', $bid, PDO::PARAM_STR);
$query-> execute();
$results = $query -> fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
foreach($results as $result)
{
	 $fdate=$result->FromDate;
	 $paymentStatus = $result->PaymentStatus;

	$a=explode("/",$fdate);
	$val=array_reverse($a);
	 $mydate =implode("/",$val);
	$cdate=date('Y/m/d');
	$date1=date_create("$cdate");
	$date2=date_create("$fdate");
 $diff=date_diff($date1,$date2);
echo $df=$diff->format("%a");
 
 // Check if payment has been completed
 if($paymentStatus == 'completed') {
     $error="You can't cancel a booking that has been paid. Please contact support for refund requests.";
 } 
 // Changed from 1 to 0 to allow immediate cancellation for unpaid bookings
 else if($df>=0)
 {
$status=2;
$cancelby='u';
$sql = "UPDATE tblbooking SET status=:status,CancelledBy=:cancelby WHERE UserEmail=:email and BookingId=:bid";
$query = $dbh->prepare($sql);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query -> bindParam(':cancelby',$cancelby , PDO::PARAM_STR);
$query-> bindParam(':email',$email, PDO::PARAM_STR);
$query-> bindParam(':bid',$bid, PDO::PARAM_STR);
$query -> execute();

$msg="Booking Cancelled successfully";
}
else
{
// Improved error message
$error="You can't cancel booking after the travel date";
}
}
}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Tour History | Nepal TMS</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- AOS Animation -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="assets/css/pages/tour-history.css" rel="stylesheet">
</head>
<body>
<?php include('includes/header.php');?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 data-aos="fade-up"><i class="fas fa-history me-3"></i>My Tour History</h1>
        <p class="mt-2" data-aos="fade-up" data-aos-delay="100">View and manage all your bookings</p>
    </div>
</section>

<!-- History Section -->
<section class="history-section">
    <div class="container">
        <?php if($error){?>
            <div class="alert alert-danger" data-aos="fade-down">
                <i class="fas fa-exclamation-circle me-2"></i><strong>ERROR:</strong> <?php echo htmlentities($error); ?>
            </div>
        <?php } else if($msg){?>
            <div class="alert alert-success" data-aos="fade-down">
                <i class="fas fa-check-circle me-2"></i><strong>SUCCESS:</strong> <?php echo htmlentities($msg); ?>
            </div>
        <?php }?>

        <?php 
        $uemail=$_SESSION['login'];
        $sql = "SELECT tblbooking.BookingId as bookid,tblbooking.PackageId as pkgid,tbltourpackages.PackageName as packagename,tblbooking.FromDate as fromdate,tblbooking.ToDate as todate,tblbooking.Comment as comment,tblbooking.status as status,tblbooking.RegDate as regdate,tblbooking.CancelledBy as cancelby,tblbooking.UpdationDate as upddate,tblbooking.Persons,tblbooking.Guide,tblbooking.Vehicle,tblbooking.PaymentAmount,tblbooking.PaymentStatus from tblbooking join tbltourpackages on tbltourpackages.PackageId=tblbooking.PackageId where UserEmail=:uemail ORDER BY tblbooking.RegDate DESC";
        $query = $dbh->prepare($sql);
        $query -> bindParam(':uemail', $uemail, PDO::PARAM_STR);
        $query->execute();
        $results=$query->fetchAll(PDO::FETCH_OBJ);
        $cnt=1;
        
        if($query->rowCount() > 0) {
            foreach($results as $result) {
                $statusClass = '';
                $statusLabel = '';
                $statusBadgeClass = '';
                
                if($result->status == 0) {
                    $statusClass = 'pending';
                    $statusLabel = 'Pending';
                    $statusBadgeClass = 'status-pending';
                } elseif($result->status == 1) {
                    $statusClass = 'confirmed';
                    $statusLabel = 'Confirmed';
                    $statusBadgeClass = 'status-confirmed';
                } elseif($result->status == 2) {
                    $statusClass = 'cancelled';
                    if($result->cancelby == 'u') {
                        $statusLabel = 'Cancelled by You';
                    } else {
                        $statusLabel = 'Cancelled by Admin';
                    }
                    $statusBadgeClass = 'status-cancelled';
                }
                
                // Determine status label and badge class
                $statusLabel = '';
                $statusBadgeClass = '';

                switch($result->status) {
                    case 0:
                        $statusLabel = 'Pending';
                        $statusBadgeClass = 'status-pending';
                        break;
                    case 1:
                        $statusLabel = 'Confirmed';
                        $statusBadgeClass = 'status-confirmed';
                        break;
                    case 2:
                        $statusLabel = 'Cancelled';
                        $statusBadgeClass = 'status-cancelled';
                        break;
                    default:
                        $statusLabel = 'Unknown';
                        $statusBadgeClass = 'status-pending';
                }

                // Determine payment status
                $paymentStatusLabel = '';
                $paymentStatusBadgeClass = '';

                if ($result->PaymentStatus == 'completed') {
                    $paymentStatusLabel = 'Paid';
                    $paymentStatusBadgeClass = 'status-confirmed';
                } else if ($result->PaymentStatus == 'pending') {
                    $paymentStatusLabel = 'Payment Pending';
                    $paymentStatusBadgeClass = 'status-pending';
                } else if ($result->PaymentStatus == 'failed') {
                    $paymentStatusLabel = 'Payment Failed';
                    $paymentStatusBadgeClass = 'status-cancelled';
                } else {
                    $paymentStatusLabel = 'Not Paid';
                    $paymentStatusBadgeClass = 'status-pending';
                }
            ?>
                <div class="booking-card <?php echo $statusClass; ?>" data-aos="fade-up" data-aos-delay="<?php echo ($cnt-1)*50; ?>">
                    <div class="booking-header">
                        <div>
                            <div class="booking-id">#BK-<?php echo htmlentities($result->bookid);?></div>
                            <div class="mt-2">
                                <span class="status-badge <?php echo $statusBadgeClass; ?> me-2">
                                    <?php echo $statusLabel; ?>
                                </span>
                                <span class="status-badge <?php echo $paymentStatusBadgeClass; ?>">
                                    <?php echo $paymentStatusLabel; ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="package-name">
                        <a href="package-details.php?pkgid=<?php echo htmlentities($result->pkgid);?>">
                            <i class="fas fa-map-marked-alt me-2"></i><?php echo htmlentities($result->packagename);?>
                        </a>
                    </div>

                    <div class="booking-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar-day"></i>
                            <div>
                                <div class="detail-label">From Date</div>
                                <div><?php echo date('d M Y', strtotime($result->fromdate));?></div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-calendar-check"></i>
                            <div>
                                <div class="detail-label">To Date</div>
                                <div><?php echo date('d M Y', strtotime($result->todate));?></div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-comment-dots"></i>
                            <div>
                                <div class="detail-label">Comment</div>
                                <div><?php echo htmlentities($result->comment);?></div>
                            </div>
                        </div>
                    </div>

                    <div class="booking-footer">
                        <div class="booking-date">
                            <i class="fas fa-clock me-2"></i>Booked on: <?php echo date('d M Y, h:i A', strtotime($result->regdate));?>
                        </div>
                        <div>
                            <?php if($result->status == 2) { ?>
                                <span class="text-muted"><i class="fas fa-ban me-2"></i>Cancelled on <?php echo date('d M Y', strtotime($result->upddate));?></span>
                            <?php } else { ?>
                                <a href="tour-history.php?bkid=<?php echo htmlentities($result->bookid);?>" 
                                   class="btn-cancel" 
                                   onclick="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.')">
                                    <i class="fas fa-times-circle me-2"></i>Cancel Booking
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php $cnt++; }
        } else { ?>
            <div class="empty-state" data-aos="fade-up">
                <i class="fas fa-clipboard-list"></i>
                <h3>No Booking History</h3>
                <p class="text-muted">You haven't made any bookings yet. Start exploring Nepal's amazing destinations!</p>
                <a href="package-list.php" class="btn-browse">
                    <i class="fas fa-search me-2"></i>Browse Packages
                </a>
            </div>
        <?php } ?>
    </div>
</section>
<?php include('includes/footer.php');?>
<!-- write us -->
<?php include('includes/write-us.php');?>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS Animation -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<!-- Custom JS -->
<script src="assets/js/pages/tour-history.js"></script>
</body>
</html>
<?php } ?>
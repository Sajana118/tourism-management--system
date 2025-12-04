<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/config.php');
include('includes/algorithms.php');

// Initialize variables
$error = '';
$msg = '';

// Initialize algorithms
$seasonalPricing = new SeasonalPricing();

// Check if user is logged in for booking
$loggedIn = isset($_SESSION['login']);

// Only process booking for authenticated users
if ($loggedIn && (isset($_POST['submit2']) || isset($_POST['form_submitted']) || ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pkgid'])))) {
    $pid=intval($_POST['pkgid'] ?? 0);
    $useremail=$_SESSION['login'] ?? '';
    $fromdate=$_POST['fromdate'] ?? '';
    $todate=$_POST['todate'] ?? '';
    $comment=$_POST['comment'] ?? '';
    $persons=$_POST['persons'] ?? '';
    $guide=isset($_POST['guide']) ? 1 : 0;
    $vehicle=isset($_POST['vehicle']) ? 1 : 0;
    $status=0;

    // Validate required fields
    if (empty($fromdate) || empty($todate) || empty($persons)) {
        $error = "Please select both dates and number of persons before booking.";
    } else {
        // Since we're now using the same date format everywhere (yy-mm-dd / yyyy-mm-dd),
        // we don't need to convert the dates - they're already in the correct format
        $fromdate_formatted = $fromdate;
        $todate_formatted = $todate;
        
        // Simple booking without slot optimization
        $sql="INSERT INTO tblbooking(PackageId,UserEmail,FromDate,ToDate,Comment,status,Persons,Guide,Vehicle) VALUES(:pid,:useremail,:fromdate,:todate,:comment,:status,:persons,:guide,:vehicle)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':pid',$pid,PDO::PARAM_STR);
        $query->bindParam(':useremail',$useremail,PDO::PARAM_STR);
        $query->bindParam(':fromdate',$fromdate_formatted,PDO::PARAM_STR);
        $query->bindParam(':todate',$todate_formatted,PDO::PARAM_STR);
        $query->bindParam(':comment',$comment,PDO::PARAM_STR);
        $query->bindParam(':status',$status,PDO::PARAM_STR);
        $query->bindParam(':persons',$persons,PDO::PARAM_STR);
        $query->bindParam(':guide',$guide,PDO::PARAM_STR);
        $query->bindParam(':vehicle',$vehicle,PDO::PARAM_STR);
        
        if($query->execute()) {
            $lastInsertId = $dbh->lastInsertId();
            if($lastInsertId) {
                // Redirect to eSewa payment page
                header('location: http://localhost/Tourism-Management-System-PHP/tms/payments/esewa/esewa_simple_redirect.php?booking_id='.$lastInsertId);
                exit();
            } else {
                $error = "Failed to get booking ID. Please try again.";
            }
        }
        else 
        {
            $error="Something went wrong. Please try again";
        }
    }
}

?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Package Details | Nepal TMS</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Leaflet CSS for Maps -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<!-- jQuery UI for Datepicker -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<!-- Custom CSS -->
<link href="assets/css/components/popup-notifications.css" rel="stylesheet">
<link href="assets/css/pages/tour-history.css" rel="stylesheet">
<link href="assets/css/pages/package-details.css" rel="stylesheet">
<style>
.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    text-align: center;
}

.status-pending {
    background-color: #fef3c7;
    color: #92400e;
    border: 1px solid #f59e0b;
}

.status-confirmed {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #10b981;
}

.status-cancelled {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #ef4444;
}
</style>
</head>
<body>
<?php include('includes/header.php');?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Package Details</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center" style="background: transparent;">
                <li class="breadcrumb-item"><a href="index.php" style="color: white; text-decoration: none;">Home</a></li>
                <li class="breadcrumb-item"><a href="package-list.php" style="color: white; text-decoration: none;">Packages</a></li>
                <li class="breadcrumb-item active" style="color: #fbbf24;">Details</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Package Details Section -->
<section class="package-detail-section">
    <div class="container">	
        <!-- Alert Messages -->
        <?php if($error){?>
            <div class="alert alert-danger alert-custom">
                <i class="fas fa-exclamation-circle me-2"></i><strong>ERROR:</strong> <?php echo htmlentities($error); ?>
            </div>
        <?php } else if($msg){?>
            <div class="alert alert-success alert-custom">
                <i class="fas fa-check-circle me-2"></i><strong>SUCCESS:</strong> <?php echo htmlentities($msg); ?>
            </div>
        <?php }?>
<?php 
$pid=intval($_GET['pkgid']);
$sql = "SELECT * from tbltourpackages where PackageId=:pid";
$query = $dbh->prepare($sql);
$query -> bindParam(':pid', $pid, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{	?>
        <div class="row">
            <div class="col-lg-8">
                <!-- Package Image -->
                <div>
                    <img src="assets/images/<?php echo htmlentities($result->PackageImage);?>" class="package-main-img" alt="<?php echo htmlentities($result->PackageName);?>">
                </div>

                <!-- Package Information -->
                <div class="package-info-card">
                    <h2 class="package-title"><?php echo htmlentities($result->PackageName);?></h2>
                    <p class="package-id"><i class="fas fa-hashtag"></i> Package ID: PKG-<?php echo htmlentities($result->PackageId);?></p>
                    
                    <div class="mt-4">
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-tag"></i> Package Type:</div>
                            <div class="info-value"><?php echo htmlentities($result->PackageType);?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-map-marker-alt"></i> Location:</div>
                            <div class="info-value"><?php echo htmlentities($result->PackageLocation);?></div>
                        </div>
                    </div>
                </div>

                <!-- Package Price with Seasonal Info -->
                <div class="package-info-card">
                    <h4 style="color: var(--nepal-blue); margin-bottom: 20px;"><i class="fas fa-tag me-2"></i>Package Pricing</h4>
                    <?php 
                    // Get today's date for seasonal pricing calculation
                    $today = date('Y-m-d');
                    
                    // Calculate seasonal price
                    $basePrice = $result->PackagePrice;
                    $seasonalPrice = $seasonalPricing->calculateSeasonalPrice($basePrice, $result->PackageLocation, $today);
                    $seasonalInfo = $seasonalPricing->getSeasonalInfo($result->PackageLocation, $today);
                    
                    // Determine if there's a price difference
                    $priceDifference = $seasonalPrice - $basePrice;
                    $hasPriceAdjustment = abs($priceDifference) > 0.01;
                    ?>
                    
                    <div class="price-card">
                        <div class="price-amount">
                            <span class="currency">NPR</span> <?php echo number_format($seasonalPrice);?>
                        </div>
                        <?php if($hasPriceAdjustment): ?>
                            <div style="font-size: 1rem; margin-top: 10px;">
                                <span style="text-decoration: line-through; opacity: 0.8;">NPR <?php echo number_format($basePrice);?></span>
                                <span style="margin-left: 10px; background: #fff; color: #DC143C; padding: 3px 8px; border-radius: 4px; font-weight: 600;">
                                    <?php echo $seasonalInfo['message']; ?>
                                </span>
                            </div>
                        <?php else: ?>
                            <div style="font-size: 1rem; margin-top: 10px;">
                                Standard pricing
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 5px; font-size: 0.9rem;">
                        <i class="fas fa-info-circle me-2" style="color: var(--nepal-blue);"></i>
                        Prices may vary based on travel dates. Book early for best rates!
                    </div>
                </div>

                <!-- Package Features -->
                <div class="package-info-card">
                    <h4 style="color: var(--nepal-blue); margin-bottom: 20px;"><i class="fas fa-star me-2"></i>Package Features</h4>
                    <ul class="features-list">
                        <?php 
                        $features = explode(',', $result->PackageFetures);
                        foreach($features as $feature) {
                            echo '<li><i class="fas fa-check-circle" style="color: #10b981;"></i>' . htmlentities(trim($feature)) . '</li>';
                        }
                        ?>
                    </ul>
                </div>

                <!-- Package Details -->
                <div class="package-info-card">
                    <h4 style="color: var(--nepal-blue); margin-bottom: 20px;"><i class="fas fa-info-circle me-2"></i>Package Details</h4>
                    <p style="color: #475569; line-height: 1.8; text-align: justify;"><?php echo htmlentities($result->PackageDetails);?></p>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Booking Form -->
                <div class="booking-form">
                    <h4 style="color: var(--nepal-blue); margin-bottom: 25px;"><i class="fas fa-calendar-check me-2"></i>Book This Package</h4>
                    
                    <?php 
                    // Check if user has already booked this package
                    if ($loggedIn) {
                        $useremail = $_SESSION['login'];
                        $checkSql = "SELECT * FROM tblbooking WHERE PackageId=:pkgid AND UserEmail=:useremail AND status IN (0,1,2)";
                        $checkQuery = $dbh->prepare($checkSql);
                        $checkQuery->bindParam(':pkgid', $pid, PDO::PARAM_INT);
                        $checkQuery->bindParam(':useremail', $useremail, PDO::PARAM_STR);
                        $checkQuery->execute();
                        
                        if($checkQuery->rowCount() > 0) {
                            // User has already booked this package
                            $booking = $checkQuery->fetch(PDO::FETCH_OBJ);
                            $statusText = '';
                            $statusClass = '';
                            $canCancel = false;
                            
                            switch($booking->status) {
                                case 0: // Pending
                                    $statusText = 'Pending Confirmation';
                                    $statusClass = 'status-pending';
                                    $canCancel = ($booking->PaymentStatus != 'completed'); // Can cancel if not paid
                                    break;
                                case 1: // Confirmed
                                    $statusText = 'Confirmed';
                                    $statusClass = 'status-confirmed';
                                    $canCancel = ($booking->PaymentStatus != 'completed'); // Can cancel if not paid
                                    break;
                                case 2: // Cancelled
                                    $statusText = 'Cancelled';
                                    $statusClass = 'status-cancelled';
                                    break;
                            }
                            ?>
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle fa-2x mb-3"></i>
                                <h5>You've Already Booked This Package</h5>
                                <p class="mb-2">Booking ID: <strong>#BK-<?php echo htmlentities($booking->BookingId); ?></strong></p>
                                <p class="mb-2">Status: <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></p>
                                <p class="mb-3">Travel Dates: <?php echo date('M d, Y', strtotime($booking->FromDate)); ?> - <?php echo date('M d, Y', strtotime($booking->ToDate)); ?></p>
                                
                                <?php if($booking->status != 2 && $canCancel) { // Not cancelled and can be cancelled ?>
                                    <p class="mb-3"><small>You can cancel this booking before the travel date.</small></p>
                                    <a href="tour-history.php?bkid=<?php echo htmlentities($booking->BookingId); ?>&cancel" class="btn btn-warning mb-2" onclick="return confirm('Are you sure you want to cancel this booking?');">
                                        <i class="fas fa-times-circle me-2"></i>Cancel Booking
                                    </a>
                                <?php } else if($booking->status != 2 && !$canCancel) { // Not cancelled but paid ?>
                                    <p class="mb-3"><small class="text-danger"><i class="fas fa-exclamation-circle me-1"></i> This booking has been paid and cannot be cancelled online. Please contact support for refund requests.</small></p>
                                <?php } else { // Already cancelled ?>
                                    <p class="mb-3"><small class="text-muted">This booking has been cancelled.</small></p>
                                <?php } ?>
                                
                                <a href="tour-history.php" class="btn btn-primary">
                                    <i class="fas fa-history me-2"></i>View Booking History
                                </a>
                            </div>
                        <?php } else { ?>
                            <form method="post" id="bookingForm" action="<?php echo $_SERVER['PHP_SELF'] . '?pkgid=' . $pid; ?>">
                                <input type="hidden" name="pkgid" value="<?php echo htmlentities($pid); ?>">
                                <input type="hidden" name="form_submitted" value="1">
                                <div class="mb-3">
                                    <label for="datepicker" class="form-label"><i class="fas fa-calendar-alt me-2"></i>From Date</label>
                                    <input type="text" class="form-control" id="datepicker" name="fromdate" placeholder="Select start date" required readonly autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label for="datepicker1" class="form-label"><i class="fas fa-calendar-alt me-2"></i>To Date</label>
                                    <input type="text" class="form-control" id="datepicker1" name="todate" placeholder="Select end date" required readonly autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label for="persons" class="form-label"><i class="fas fa-users me-2"></i>Number of Persons</label>
                                    <input type="number" class="form-control" id="persons" name="persons" min="1" max="20" value="1" required autocomplete="number">
                                    <div class="form-text">Select the number of people traveling</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-concierge-bell me-2"></i>Additional Services</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="guide" name="guide" value="1" autocomplete="off">
                                        <label class="form-check-label" for="guide">
                                            Tour Guide (+ NPR 1000 per day)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="vehicle" name="vehicle" value="1" autocomplete="off">
                                        <label class="form-check-label" for="vehicle">
                                            Vehicle Transportation (+ NPR 2000 per day)
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="comment" class="form-label"><i class="fas fa-comment me-2"></i>Special Requests</label>
                                    <textarea class="form-control" name="comment" id="comment" rows="3" placeholder="Any special requirements?" autocomplete="off"></textarea>
                                </div>
                                <?php if($loggedIn) { ?>
                                    <button type="submit" name="submit2" class="btn-book" id="bookNowBtn">
                                        <i class="fas fa-check-circle me-2"></i>Book Now
                                    </button>
                                <?php } else { ?>
                                    <a href="#" class="btn-book" data-bs-toggle="modal" data-bs-target="#loginModal">
                                        <i class="fas fa-sign-in-alt me-2"></i>Sign In to Book
                                    </a>
                                <?php } ?>
                            </form>
                        <?php } ?>
                    <?php } else { ?>
                        <!-- Show booking form with login prompt for unauthenticated users -->
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <h5>Please Sign In to Book</h5>
                            <p class="mb-3">You need to be signed in to book this package.</p>
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-sign-in-alt me-2"></i>Sign In to Book
                            </a>
                        </div>
                    <?php } ?>
                    
                    <div class="mt-4 text-center">
                        <small class="text-muted"><i class="fas fa-shield-alt me-1"></i>100% Secure Booking</small>
                    </div>
                </div>

                <!-- Location Map -->
                <div class="package-info-card mt-4">
                    <h4 style="color: var(--nepal-blue); margin-bottom: 20px;"><i class="fas fa-map-marked-alt me-2"></i>Tour Location</h4>
                    <div id="locationMap"></div>
                    <!-- Data elements for JavaScript -->
                    <div id="package-location-data" data-location="<?php echo isset($result) ? htmlentities($result->PackageLocation) : 'Kathmandu'; ?>" style="display: none;"></div>
                    <div id="package-name-data" data-name="<?php echo isset($result) ? htmlentities($result->PackageName) : 'Nepal Tour'; ?>" style="display: none;"></div>
                    <p style="margin-top: 15px; color: #64748b; font-size: 0.9rem;">
                        <i class="fas fa-info-circle me-2"></i>Interactive map showing the tour location in Nepal
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Map initialization handled by external JS file -->
    </div>
</section>
<?php 
        } // Close foreach loop
        } // Close if statement
        ?>
        
        <?php include('includes/footer.php');?>
<!-- write us -->
<?php include('includes/write-us.php');?>

<!-- Login and Signup Modals -->
<?php include('includes/login-modal.php'); ?>
<?php include('includes/signup-modal.php'); ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- jQuery UI -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<!-- Leaflet JS for Maps -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="assets/js/components/popup-notifications.js"></script>
<script src="assets/js/pages/package-details.js"></script>

<!-- Booking Form Validation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('bookingForm');
    const bookNowBtn = document.getElementById('bookNowBtn');
    
    if (bookingForm && bookNowBtn) {
        bookingForm.addEventListener('submit', function(e) {
            const fromDate = document.getElementById('datepicker').value;
            const toDate = document.getElementById('datepicker1').value;
            const persons = document.getElementById('persons').value;
            
            // Check if required fields are filled
            if (!fromDate || !toDate || !persons) {
                e.preventDefault();
                if (typeof PopupNotification !== "undefined") {
                    PopupNotification.error('Please select both dates and number of persons before booking.');
                } else {
                    alert('Please select both dates and number of persons before booking.');
                }
                return false;
            }
            
            // Check if from date is before to date
            // Since we're now using ISO format (yyyy-mm-dd), we can directly compare strings
            if (fromDate > toDate) {
                e.preventDefault();
                if (typeof PopupNotification !== "undefined") {
                    PopupNotification.error('From date must be before or equal to To date.');
                } else {
                    alert('From date must be before or equal to To date.');
                }
                return false;
            }
            
            // Show loading state
            bookNowBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            bookNowBtn.disabled = true;
            
            // Allow form to submit normally - don't prevent default
            console.log('Form validation passed, allowing submission');
        });
    }
});
</script>
</body>
</html>
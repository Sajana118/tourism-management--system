<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../../config/database.php');

// Debug: Log session and request data
error_log("=== eSewa Redirect Debug ===");
error_log("Session data: " . print_r($_SESSION, true));
error_log("GET data: " . print_r($_GET, true));

// Check if user is logged in
if (!isset($_SESSION['login'])) {
    error_log("User not logged in, redirecting to index.php with login prompt");
    header('location: ../../index.php#login');
    exit();
}

// Check if booking ID is provided
if (!isset($_GET['booking_id']) || !is_numeric($_GET['booking_id'])) {
    error_log("Invalid booking ID: " . ($_GET['booking_id'] ?? 'none'));
    header('location: ../../package-list.php');
    exit();
}

$booking_id = intval($_GET['booking_id']);
error_log("Processing booking ID: $booking_id");

// Fetch booking details
$sql = "SELECT b.*, p.PackageName, p.PackagePrice, p.PackageLocation, u.id as UserId 
        FROM tblbooking b 
        JOIN tbltourpackages p ON b.PackageId = p.PackageId 
        JOIN tblusers u ON b.UserEmail = u.EmailId 
        WHERE b.BookingId = :booking_id AND b.UserEmail = :user_email";

$query = $dbh->prepare($sql);
$query->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
$query->bindParam(':user_email', $_SESSION['login'], PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);

error_log("Query result: " . print_r($result, true));

// Check if booking exists
if (!$result) {
    error_log("Booking not found for ID: $booking_id, user: " . $_SESSION['login']);
    header('location: ../../tour-history.php');
    exit();
}

// Calculate seasonal price
include('../../includes/algorithms.php');
$seasonalPricing = new SeasonalPricing();
$basePrice = $result->PackagePrice;
$today = date('Y-m-d');
$seasonalPrice = $seasonalPricing->calculateSeasonalPrice($basePrice, $result->PackageLocation, $today);

// Calculate total price based on number of persons and additional services
$persons = $result->Persons ? $result->Persons : 1;
$guide = $result->Guide ? $result->Guide : 0;
$vehicle = $result->Vehicle ? $result->Vehicle : 0;

// Base price per person
$pricePerPerson = $seasonalPrice;
$totalBasePrice = $pricePerPerson * $persons;

// Calculate additional services cost
$guideCost = 0;
$vehicleCost = 0;

// Calculate number of days for the trip
// Since we're using the same date format everywhere, we can directly create DateTime objects
$fromDate = new DateTime($result->FromDate);
$toDate = new DateTime($result->ToDate);
$interval = $fromDate->diff($toDate);
$days = $interval->days + 1; // Include both start and end dates

// Ensure days is reasonable (max 30 days)
if ($days > 30) {
    $days = 30;
}

// Ensure days is at least 1
if ($days < 1) {
    $days = 1;
}

error_log("Days calculated: " . $days);

if ($guide) {
    $guideCost = 1000 * $days; // NPR 1000 per day for guide
}

if ($vehicle) {
    $vehicleCost = 2000 * $days; // NPR 2000 per day for vehicle
}

// Total amount calculation
$amount = $totalBasePrice + $guideCost + $vehicleCost;
$tax_amount = 0; // No tax for simplicity
$product_service_charge = 0; // No service charge
$product_delivery_charge = 0; // No delivery charge
$total_amount = $amount + $tax_amount + $product_service_charge + $product_delivery_charge;

// Generate unique transaction UUID
$transaction_uuid = $booking_id . '-' . time();

// eSewa configuration - CORRECT secret key from working "tourism" project
$product_code = 'EPAYTEST';
$secret_key = '8gBm/:&EnhH.1/q'; // WITHOUT the trailing parenthesis

// Generate signature - EXACT approach from your "tourism" project
$message = "total_amount=" . $total_amount . ",transaction_uuid=" . $transaction_uuid . ",product_code=" . $product_code;
$s = hash_hmac('sha256', $message, $secret_key, true);
$signature = base64_encode($s);

// Update booking with payment amount
$update_sql = "UPDATE tblbooking SET PaymentAmount = :amount WHERE BookingId = :booking_id";
$update_query = $dbh->prepare($update_sql);
$update_query->bindParam(':amount', $total_amount, PDO::PARAM_STR);
$update_query->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
$update_query->execute();

// Create payment record
$payment_sql = "INSERT INTO tblpayment (BookingId, UserId, Amount, PaymentMethod, PaymentStatus) 
                VALUES (:booking_id, :user_id, :amount, 'eSewa', 'pending')";
$payment_query = $dbh->prepare($payment_sql);
$payment_query->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
$payment_query->bindParam(':user_id', $result->UserId, PDO::PARAM_INT);
$payment_query->bindParam(':amount', $total_amount, PDO::PARAM_STR);
$payment_query->execute();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to eSewa</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --nepal-red: #DC143C;
            --nepal-blue: #003893;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .payment-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .payment-header {
            text-align: center;
            margin-bottom: 30px;
            color: var(--nepal-blue);
        }
        .package-info {
            background: #f1f5f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .payment-details {
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .btn-esewa {
            background: #61b34d;
            color: white;
            padding: 12px 30px;
            font-weight: 600;
            border: none;
            width: 100%;
            font-size: 1.1rem;
        }
        .btn-esewa:hover {
            background: #50a03d;
        }
        .price-breakdown {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <h2><i class="fas fa-credit-card me-2"></i>eSewa Payment</h2>
            <p class="text-muted">Complete your payment securely through eSewa</p>
        </div>

        <div class="package-info">
            <h4><i class="fas fa-map-marked-alt me-2"></i><?php echo htmlentities($result->PackageName); ?></h4>
            <div class="row mt-3">
                <div class="col-md-6">
                    <p><strong>Booking ID:</strong> #BK-<?php echo str_pad($result->BookingId, 4, '0', STR_PAD_LEFT); ?></p>
                    <p><strong>Travel Dates:</strong> <?php echo htmlentities($result->FromDate); ?> to <?php echo htmlentities($result->ToDate); ?></p>
                    <p><strong>Duration:</strong> <?php echo $days; ?> day(s)</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Number of Persons:</strong> <?php echo $persons; ?></p>
                    <?php if($guide): ?>
                    <p><strong>Tour Guide:</strong> Included</p>
                    <?php endif; ?>
                    <?php if($vehicle): ?>
                    <p><strong>Vehicle:</strong> Included</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="payment-details">
            <h5><i class="fas fa-receipt me-2"></i>Price Breakdown</h5>
            <div class="price-breakdown">
                <p><strong>Base Price:</strong> NPR <?php echo number_format($pricePerPerson); ?> × <?php echo $persons; ?> person(s) = NPR <?php echo number_format($totalBasePrice); ?></p>
                <?php if($guide): ?>
                <p><strong>Tour Guide:</strong> NPR 1,000 × <?php echo $days; ?> day(s) = NPR <?php echo number_format($guideCost); ?></p>
                <?php endif; ?>
                <?php if($vehicle): ?>
                <p><strong>Vehicle:</strong> NPR 2,000 × <?php echo $days; ?> day(s) = NPR <?php echo number_format($vehicleCost); ?></p>
                <?php endif; ?>
                <hr>
                <p><strong>Total Amount:</strong> NPR <?php echo number_format($total_amount); ?></p>
            </div>
            
            <h5 class="mt-4"><i class="fas fa-info-circle me-2"></i>Payment Instructions</h5>
            <ol class="mt-3">
                <li>You will be redirected to eSewa payment gateway</li>
                <li>Login to your eSewa account</li>
                <li>Verify the payment amount (NPR <?php echo number_format($total_amount); ?>)</li>
                <li>Complete the payment using your preferred method</li>
                <li>You will be redirected back to our website after payment</li>
            </ol>
        </div>

        <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST" id="esewaform">
            <input type="hidden" name="amount" value="<?php echo $amount; ?>">
            <input type="hidden" name="tax_amount" value="<?php echo $tax_amount; ?>">
            <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
            <input type="hidden" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>">
            <input type="hidden" name="product_code" value="<?php echo $product_code; ?>">
            <input type="hidden" name="product_service_charge" value="<?php echo $product_service_charge; ?>">
            <input type="hidden" name="product_delivery_charge" value="<?php echo $product_delivery_charge; ?>">
            <input type="hidden" name="success_url" value="http://localhost/Tourism-Management-System-PHP/tms/payments/esewa/esewa_success.php?booking_id=<?php echo $booking_id; ?>">
            <input type="hidden" name="failure_url" value="http://localhost/Tourism-Management-System-PHP/tms/payments/esewa/esewa_failure.php?booking_id=<?php echo $booking_id; ?>">
            <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
            <input type="hidden" name="signature" value="<?php echo $signature; ?>">
            <button type="submit" class="btn-esewa">
                <i class="fas fa-lock me-2"></i>Pay with eSewa (NPR <?php echo number_format($total_amount); ?>)
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="text-muted mb-3">Click the button below to proceed with your payment</p>
            <a href="../../tour-history.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Bookings
            </a>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!--
    <script>
        // Auto-submit the form after 3 seconds
        // Commented out to prevent automatic redirect
        // setTimeout(function() {
        //     document.getElementById('esewaform').submit();
        // }, 3000);
    </script>
    -->
</body>
</html>
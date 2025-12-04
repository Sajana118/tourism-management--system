<?php
session_start();
error_reporting(0);
include('../../config/database.php');
include('esewa_signature.php');

// Check if user is logged in
if (!isset($_SESSION['login'])) {
    header('location: ../../index.php#login');
    exit();
}

// For testing purposes, we'll create a simple success page
// In a real implementation, eSewa would send a Base64 encoded response

// Set success message
$_SESSION['msg'] = "Payment completed successfully! Your booking is now confirmed.";

// For demonstration, we'll redirect to tour history
header('location: ../../tour-history.php');
exit();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success | Nepal TMS</title>
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
        .success-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-icon {
            font-size: 4rem;
            color: #10b981;
            margin-bottom: 20px;
        }
        .btn-primary {
            background: var(--nepal-red);
            border: none;
        }
        .btn-primary:hover {
            background: #c41136;
        }
    </style>
</head>
<body>
    <?php include('includes/header.php');?>

    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2>Payment Successful!</h2>
        <p class="lead">Your payment of NPR <?php echo number_format($amount); ?> has been processed successfully.</p>
        <p>Transaction Reference: <?php echo htmlentities($ref_id); ?></p>
        <p>Payment ID: <?php echo $payment_id; ?></p>
        
        <div class="alert alert-success mt-4">
            <i class="fas fa-info-circle me-2"></i>
            Your booking is now confirmed and ready for your trip!
        </div>
        
        <div class="mt-4">
            <a href="tour-history.php" class="btn btn-primary">
                <i class="fas fa-history me-2"></i>View Booking History
            </a>
            <a href="package-list.php" class="btn btn-outline-primary ms-2">
                <i class="fas fa-search me-2"></i>Browse More Packages
            </a>
        </div>
    </div>

    <?php include('includes/footer.php');?>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
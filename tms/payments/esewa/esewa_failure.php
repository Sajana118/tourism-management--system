<?php
session_start();
error_reporting(0);
include('../../config/database.php');

// Check if user is logged in
if (!isset($_SESSION['login'])) {
    header('location: ../../index.php#login');
    exit();
}

// Set error message
$_SESSION['error'] = "Payment was cancelled or failed. Please try again or contact support if you continue to experience issues.";

// Redirect to tour history page
header('location: ../../tour-history.php');
exit();
?>
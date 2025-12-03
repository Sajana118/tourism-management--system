<?php
session_start();
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us | Nepal Tourism Management System</title>
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- AOS Animation -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="assets/css/components/popup-notifications.css" rel="stylesheet">
<link href="assets/css/pages/about-us.css" rel="stylesheet">
</head>
<body>
<?php include('includes/header.php');?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 data-aos="fade-up">About Us</h1>
        <p class="lead" data-aos="fade-up" data-aos-delay="100">Discover the beauty of Nepal with our expertly crafted tourism experiences</p>
    </div>
</section>

<!-- Shortened Content Section -->
<section class="content-section">
    <div class="container">
        <div class="content-card" data-aos="fade-up">
            <h2>About Nepal Tourism Management System</h2>
            <p>Welcome to Nepal Tourism Management System (TMS), your gateway to the breathtaking landscapes and rich cultural heritage of Nepal. We specialize in creating unforgettable travel experiences for adventurers and culture enthusiasts from around the globe.</p>
            
            <h3>Our Mission</h3>
            <p>Our mission is to provide exceptional travel experiences that showcase the authentic beauty of Nepal while promoting responsible tourism. We focus on creating memorable journeys that connect travelers with Nepal's rich culture and natural beauty.</p>
            
            <h3>Why Choose Us?</h3>
            <ul>
                <li>Expertly crafted tour packages tailored to your interests</li>
                <li>Local guides with deep knowledge of Nepal's culture and geography</li>
                <li>Commitment to sustainable and responsible tourism</li>
                <li>24/7 customer support for a seamless travel experience</li>
            </ul>
        </div>
    </div>
</section>



<?php include('includes/footer.php');?>
<!-- write us -->
<?php include('includes/write-us.php');?>

<!-- Login and Signup Modals -->
<?php include('includes/login-modal.php');?>
<?php include('includes/signup-modal.php');?>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS Animation -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<!-- Custom JS -->
<script src="assets/js/components/popup-notifications.js"></script>
<script src="assets/js/pages/about-us.js"></script>
</body>
</html>
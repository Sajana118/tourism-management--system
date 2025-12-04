<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/algorithms.php');

// Initialize recommendation engine
$recommendationEngine = new RecommendationEngine($dbh);
$seasonalPricing = new SeasonalPricing();

// Get recommended packages if user is logged in
$recommendedPackages = [];
if(isset($_SESSION['login'])) {
    $recommendedIds = $recommendationEngine->getRecommendedPackages($_SESSION['login'], 4);
    if(!empty($recommendedIds)) {
        $placeholders = implode(',', array_fill(0, count($recommendedIds), '?'));
        $sql = "SELECT * FROM tbltourpackages WHERE PackageId IN ($placeholders)";
        $query = $dbh->prepare($sql);
        $query->execute($recommendedIds);
        $recommendedPackages = $query->fetchAll(PDO::FETCH_OBJ);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Nepal TMS | Explore the Himalayas - Tourism Management System</title>
<meta name="description" content="Discover Nepal's breathtaking destinations with Nepal Tourism Management System. Book packages to Kathmandu, Pokhara, Everest Base Camp, and more.">
<meta name="keywords" content="Nepal tourism, Nepal tours, Himalaya tours, Kathmandu, Pokhara, Everest, Nepal packages">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- Custom CSS -->
<link href="assets/css/style.css" rel='stylesheet' type='text/css' />
<link href="assets/css/pages/index.css" rel="stylesheet">

</head>
<body>
<?php include('includes/header.php');?>

<!-- Hero Banner -->
<section class="hero-banner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 hero-content">
                <h1>Discover the Himalayas </h1>
                <p>Experience Nepal's Majestic Beauty - From Sacred Temples to Mountain Peaks</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="#packages" class="btn-nepal"><i class="fas fa-compass me-2"></i>Explore Packages</a>
                    <a href="#" class="btn btn-outline-light btn-lg rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#videoModal"><i class="fas fa-play-circle me-2"></i>Watch Video</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recommended Packages Section (Algorithm 1: Collaborative Filtering) -->
<?php if(isset($_SESSION['login']) && !empty($recommendedPackages)) { ?>
<section class="py-5" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-top: 3px solid var(--nepal-blue);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">
                <i class="fas fa-magic" style="color: var(--nepal-red);"></i> 
                Recommended Just For You
            </h2>
        </div>
        
        <div class="row">
        <?php 
        $recCnt = 1;
        foreach($recommendedPackages as $recPkg) { ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="package-card" style="border: 2px solid var(--nepal-red); position: relative; height: 100%; display: flex; flex-direction: column;">
                    <span style="position: absolute; top: -12px; left: 20px; background: var(--nepal-red); color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; z-index: 10; white-space: nowrap;">
                        <i class="fas fa-star"></i> Recommended
                    </span>
                    <div class="package-img-wrapper">
                        <img src="assets/images/<?php echo htmlentities($recPkg->PackageImage);?>" class="package-img" alt="<?php echo htmlentities($recPkg->PackageName);?>" style="height: 200px; object-fit: cover;">
                        <span class="package-badge"><?php echo htmlentities($recPkg->PackageType);?></span>
                    </div>
                    <div class="package-body" style="flex-grow: 1; display: flex; flex-direction: column;">
                        <h3 class="package-title"><?php echo htmlentities($recPkg->PackageName);?></h3>
                        <div class="package-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlentities($recPkg->PackageLocation);?>
                        </div>
                        <p class="package-features" style="flex-grow: 1;"><?php echo substr(htmlentities($recPkg->PackageFetures), 0, 100); ?>...</p>
                        <div class="package-footer">
                            <div class="package-price">
                                <?php 
                                // Calculate seasonal price for recommended packages
                                $basePrice = $recPkg->PackagePrice;
                                $today = date('Y-m-d');
                                $seasonalPrice = $seasonalPricing->calculateSeasonalPrice($basePrice, $recPkg->PackageLocation, $today);
                                $priceDifference = $seasonalPrice - $basePrice;
                                $hasPriceAdjustment = abs($priceDifference) > 0.01;
                                
                                if($hasPriceAdjustment): ?>
                                    <span class="currency">NPR</span> <span style="text-decoration: line-through; font-size: 0.9rem; opacity: 0.8;"><?php echo number_format($basePrice);?></span>
                                    <span style="color: #DC143C; font-weight: 700;"> <?php echo number_format($seasonalPrice);?></span>
                                <?php else: ?>
                                    <span class="currency">NPR</span> <?php echo number_format($seasonalPrice);?>
                                <?php endif; ?>
                            </div>
                            <a href="package-details.php?pkgid=<?php echo htmlentities($recPkg->PackageId);?>" class="btn-details">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php $recCnt++; } ?>
        </div>
    </div>
</section>
<?php } else if(isset($_SESSION['login'])) { ?>
<!-- Message for users with no booking history -->
<section class="py-5" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-top: 3px solid var(--nepal-blue);">
    <div class="container">
        <div class="text-center">
            <h2 class="section-title">
                <i class="fas fa-magic" style="color: var(--nepal-red);"></i> 
                Personalized Recommendations Coming Soon
            </h2>
            <p class="text-muted mt-3" style="font-size: 1.1rem;">Start booking packages to receive personalized recommendations based on your travel preferences.</p>
            <a href="package-list.php" class="btn-nepal btn-lg mt-3"><i class="fas fa-th-large me-2"></i>Explore Packages</a>
        </div>
    </div>
</section>
<?php } ?>

<!-- Packages Section -->
<section id="packages" class="py-5" style="background: var(--bg-light);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Featured Nepal Packages</h2>
            <p class="text-muted mt-3" style="font-size: 1.1rem;">Handpicked destinations across Nepal - Mountains, Culture & Adventure</p>
        </div>

        <div class="row">
<?php $sql = "SELECT * from tbltourpackages order by rand() limit 6";
$query = $dbh->prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{	?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="package-card">
                    <div class="package-img-wrapper">
                        <img src="assets/images/<?php echo htmlentities($result->PackageImage);?>" class="package-img" alt="<?php echo htmlentities($result->PackageName);?>">
                        <span class="package-badge"><?php echo htmlentities($result->PackageType);?></span>
                    </div>
                    <div class="package-body">
                        <h3 class="package-title"><?php echo htmlentities($result->PackageName);?></h3>
                        <div class="package-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlentities($result->PackageLocation);?>
                        </div>
                        <p class="package-features"><?php echo substr(htmlentities($result->PackageFetures), 0, 100); ?>...</p>
                        <div class="package-footer">
                            <div class="package-price">
                                <?php 
                                // Calculate seasonal price for featured packages
                                $basePrice = $result->PackagePrice;
                                $today = date('Y-m-d');
                                $seasonalPrice = $seasonalPricing->calculateSeasonalPrice($basePrice, $result->PackageLocation, $today);
                                $priceDifference = $seasonalPrice - $basePrice;
                                $hasPriceAdjustment = abs($priceDifference) > 0.01;
                                
                                if($hasPriceAdjustment): ?>
                                    <span class="currency">NPR</span> <span style="text-decoration: line-through; font-size: 0.9rem; opacity: 0.8;"><?php echo number_format($basePrice);?></span>
                                    <span style="color: #DC143C; font-weight: 700;"> <?php echo number_format($seasonalPrice);?></span>
                                <?php else: ?>
                                    <span class="currency">NPR</span> <?php echo number_format($seasonalPrice);?>
                                <?php endif; ?>
                            </div>
                            <a href="package-details.php?pkgid=<?php echo htmlentities($result->PackageId);?>" class="btn-details">View Details</a>
                        </div>
                    </div>
                </div>
            </div>

<?php $cnt++; }} ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="package-list.php" class="btn-nepal btn-lg"><i class="fas fa-th-large me-2"></i>View All Packages</a>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-route"></i></div>
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Nepal Destinations</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="200">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-number">
                        <?php 
                        $sql_users = "SELECT COUNT(*) as total FROM tblusers";
                        $query_users = $dbh->prepare($sql_users);
                        $query_users->execute();
                        $users = $query_users->fetch(PDO::FETCH_OBJ);
                        echo $users->total > 0 ? $users->total : '0';
                        ?>+
                    </div>
                    <div class="stat-label">Happy Travelers</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="300">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-number">
                        <?php 
                        $sql_bookings = "SELECT COUNT(*) as total FROM tblbooking";
                        $query_bookings = $dbh->prepare($sql_bookings);
                        $query_bookings->execute();
                        $bookings = $query_bookings->fetch(PDO::FETCH_OBJ);
                        echo $bookings->total > 0 ? $bookings->total : '0';
                        ?>+
                    </div>
                    <div class="stat-label">Tours Completed</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="400">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-star"></i></div>
                    <div class="stat-number">4.9/5</div>
                    <div class="stat-label">Customer Rating</div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('includes/footer.php');?>
<!-- write us -->
<?php include('includes/write-us.php');?>			
<!-- //write us -->

<!-- Login and Signup Modals -->
<?php include('includes/login-modal.php'); ?>
<?php include('includes/signup-modal.php'); ?>

<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content" style="background: #000;">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white">Experience Nepal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center justify-content-center p-0">
                <video width="100%" height="100%" controls muted style="max-height: 85vh;">
                    <source src="assets/images/nepal.mp4" type="video/mp4">
                    <source src="nepal.mp4" type="video/mp4">
                    Your browser does not support the video tag. Please make sure nepal.mp4 exists in the images folder.
                </video>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Check if URL contains #login and open login modal
window.addEventListener('DOMContentLoaded', function() {
    if (window.location.hash === '#login') {
        // Remove the hash from URL without reloading the page
        history.replaceState(null, null, ' ');
        
        // Show login modal after a short delay to ensure DOM is ready
        setTimeout(function() {
            var loginModalElement = document.getElementById('loginModal');
            if (loginModalElement) {
                var loginModal = new bootstrap.Modal(loginModalElement);
                loginModal.show();
            }
        }, 500);
    }
});
</script>

</body>
</html>
<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/algorithms.php');

// Initialize seasonal pricing and smart filter
$seasonalPricing = new SeasonalPricing();
$smartFilter = new SmartFilter($dbh);

// Get filter parameters
$filterType = isset($_GET['type']) ? $_GET['type'] : '';
$filterLocation = isset($_GET['location']) ? $_GET['location'] : '';
$filterPrice = isset($_GET['price']) ? $_GET['price'] : '';

// Prepare filters array
$filters = [
    'type' => $filterType,
    'location' => $filterLocation,
    'price_range' => $filterPrice
];

// Get packages using smart filter
// Check if any filters are applied
$hasFilters = !empty($filterType) || !empty($filterLocation) || !empty($filterPrice);

if (isset($_SESSION['login']) && !$hasFilters) {
    // For logged-in users with no filters, use personalized recommendations
    $results = $smartFilter->getPersonalizedRecommendations($_SESSION['login']);
} else {
    // For guests or when filters are applied, use smart filtering
    $results = $smartFilter->getSmartRecommendations($filters);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Nepal Tour Packages | Explore Nepal - TMS</title>
<meta name="description" content="Browse all Nepal tour packages - Kathmandu, Pokhara, Everest, Chitwan. Best prices, curated itineraries.">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Leaflet Maps -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<!-- AOS Animation -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Custom CSS -->
<link href="assets/css/components/popup-notifications.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
<style>
    :root {
        --nepal-red: #DC143C;
        --nepal-blue: #003893;
        --bg-light: #f8fafc;
    }
    
    .filter-sidebar {
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .filter-section {
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    
    .filter-section:last-child {
        border-bottom: none;
    }
    
    .filter-title {
        font-weight: 600;
        color: var(--nepal-blue);
        margin-bottom: 15px;
        font-size: 1.1rem;
    }
    
    .filter-option {
        display: block;
        margin-bottom: 8px;
        cursor: pointer;
    }
    
    .filter-option input {
        margin-right: 8px;
    }
    
    .package-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .package-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    .package-img-wrapper {
        position: relative;
        overflow: hidden;
    }
    
    .package-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .package-card:hover .package-img {
        transform: scale(1.05);
    }
    
    .package-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--nepal-red);
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .package-body {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .package-title {
        color: var(--nepal-blue);
        margin-bottom: 10px;
        font-size: 1.2rem;
    }
    
    .package-location {
        color: #64748b;
        margin-bottom: 15px;
        font-size: 0.9rem;
    }
    
    .package-features {
        color: #475569;
        font-size: 0.9rem;
        margin-bottom: 20px;
        flex-grow: 1;
    }
    
    .package-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }
    
    .package-price {
        font-weight: 700;
        color: var(--nepal-red);
        font-size: 1.3rem;
    }
    
    .currency {
        font-size: 1rem;
    }
    
    .btn-details {
        background: var(--nepal-blue);
        color: white;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-details:hover {
        background: #002a6e;
        color: white;
    }
    
    .active-filter {
        background: var(--nepal-blue);
        color: white;
    }
</style>
</head>
<body>
<?php include('includes/header.php');?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Explore Nepal Packages</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Packages</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Packages Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Filter Sidebar -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <h3 class="filter-title"><i class="fas fa-filter me-2"></i>Filter Packages</h3>
                    
                    <form method="GET" id="filterForm">
                        <!-- Package Type Filter -->
                        <div class="filter-section">
                            <h4 class="filter-title">Package Type</h4>
                            <label class="filter-option">
                                <input type="radio" name="type" value="" <?php echo empty($filterType) ? 'checked' : ''; ?>> All Types
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="type" value="Adventure Package" <?php echo $filterType === 'Adventure Package' ? 'checked' : ''; ?>> Adventure
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="type" value="Trekking Package" <?php echo $filterType === 'Trekking Package' ? 'checked' : ''; ?>> Trekking
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="type" value="Cultural Package" <?php echo $filterType === 'Cultural Package' ? 'checked' : ''; ?>> Cultural
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="type" value="Wildlife Package" <?php echo $filterType === 'Wildlife Package' ? 'checked' : ''; ?>> Wildlife
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="type" value="Spiritual Package" <?php echo $filterType === 'Spiritual Package' ? 'checked' : ''; ?>> Spiritual
                            </label>
                        </div>
                        
                        <!-- Location Filter -->
                        <div class="filter-section">
                            <h4 class="filter-title">Location</h4>
                            <label class="filter-option">
                                <input type="radio" name="location" value="" <?php echo empty($filterLocation) ? 'checked' : ''; ?>> All Locations
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="location" value="Kathmandu" <?php echo $filterLocation === 'Kathmandu' ? 'checked' : ''; ?>> Kathmandu
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="location" value="Pokhara" <?php echo $filterLocation === 'Pokhara' ? 'checked' : ''; ?>> Pokhara
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="location" value="Everest" <?php echo $filterLocation === 'Everest' ? 'checked' : ''; ?>> Everest Region
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="location" value="Annapurna" <?php echo $filterLocation === 'Annapurna' ? 'checked' : ''; ?>> Annapurna Region
                            </label>
                        </div>
                        
                        <!-- Price Range Filter -->
                        <div class="filter-section">
                            <h4 class="filter-title">Price Range</h4>
                            <label class="filter-option">
                                <input type="radio" name="price" value="" <?php echo empty($filterPrice) ? 'checked' : ''; ?>> All Prices
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="price" value="0-5000" <?php echo $filterPrice === '0-5000' ? 'checked' : ''; ?>> NPR 0 - 5,000
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="price" value="5000-10000" <?php echo $filterPrice === '5000-10000' ? 'checked' : ''; ?>> NPR 5,000 - 10,000
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="price" value="10000-20000" <?php echo $filterPrice === '10000-20000' ? 'checked' : ''; ?>> NPR 10,000 - 20,000
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="price" value="20000+" <?php echo $filterPrice === '20000+' ? 'checked' : ''; ?>> NPR 20,000+
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        <?php if (!empty($filterType) || !empty($filterLocation) || !empty($filterPrice)): ?>
                            <a href="package-list.php" class="btn btn-outline-secondary w-100 mt-2">Clear Filters</a>
                        <?php endif; ?>
                    </form>
                </div>
                
                <!-- Recommendation Info -->

            </div>
            
            <!-- Packages Display -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Available Packages</h2>
                    <div class="text-muted">
                        <?php echo count($results); ?> packages found
                    </div>
                </div>
                
                <?php if (!empty($filterType) || !empty($filterLocation) || !empty($filterPrice)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-filter me-2"></i>
                        Filtered by: 
                        <?php 
                        $activeFilters = [];
                        if (!empty($filterType)) $activeFilters[] = "Type: " . $filterType;
                        if (!empty($filterLocation)) $activeFilters[] = "Location: " . $filterLocation;
                        if (!empty($filterPrice)) $activeFilters[] = "Price: " . str_replace('-', ' - ', $filterPrice);
                        echo implode(', ', $activeFilters);
                        ?>
                    </div>
                <?php endif; ?>
                
                <div class="row">
                    <?php if(!empty($results)) {
                    foreach($results as $result) { ?>
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
                                            // Calculate seasonal price for packages
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
                    <?php } } else { ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-search fa-3x mb-3" style="color: #ccc;"></i>
                                <h3>No packages found</h3>
                                <p class="text-muted">Try adjusting your filters or check back later for new packages.</p>
                                <a href="package-list.php" class="btn btn-primary">View All Packages</a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
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
<script src="assets/js/pages/package-list.js"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true
    });
    
    // Auto-submit form when filter options change
    document.querySelectorAll('input[name="type"], input[name="location"], input[name="price"]').forEach(function(element) {
        element.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
</script>
</body>
</html>
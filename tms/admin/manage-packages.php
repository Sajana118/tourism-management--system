<?php 
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0){	
header('location:index.php');
}else{ 
// Code for deletion
if($_GET['action']=='delete')
{
$id=intval($_GET['id']);
//$query=mysqli_query($con,"delete from tbltourpackages where PackageId =:id");
$sql ="delete from tbltourpackages where PackageId =:id";
$query = $dbh -> prepare($sql);
$query -> bindParam(':id', $id, PDO::PARAM_STR);
$query->execute();
echo "<script>alert('Package deleted.');</script>";
echo "<script>window.location.href='manage-packages.php'</script>";

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Packages | TMS Admin</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Custom CSS -->
<link href="../assets/css/pages/admin-dashboard.css" rel="stylesheet">
<link href="../assets/css/pages/admin-manage-packages.css" rel="stylesheet">
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
        <li><a href="manage-packages.php" class="active"><i class="fas fa-box"></i> Tour Packages</a></li>
        <li><a href="create-package.php"><i class="fas fa-plus-circle"></i> Create Package</a></li>
        <li><a href="manage-bookings.php"><i class="fas fa-calendar-check"></i> Bookings</a></li>
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
            <h5><i class="fas fa-box me-2"></i>Manage Packages</h5>
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
                <li class="breadcrumb-item active" aria-current="page">Manage Packages</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-box me-2"></i>Tour Packages</h5>
                <a href="create-package.php" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i>Create New Package
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Price (Rs)</th>
                            <th>Creation Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sql = "SELECT * from tblTourPackages";
                        $query = $dbh -> prepare($sql);
                        //$query -> bindParam(':city', $city, PDO::PARAM_STR);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                        $cnt=1;
                        if($query->rowCount() > 0)
                        {
                        foreach($results as $result)
                        {				
                        ?>
                        <tr>
                            <td><?php echo htmlentities($cnt);?></td>
                            <td><?php echo htmlentities($result->PackageName);?></td>
                            <td><?php echo htmlentities($result->PackageType);?></td>
                            <td><?php echo htmlentities($result->PackageLocation);?></td>
                            <td>
                                Rs. <?php echo htmlentities($result->PackagePrice);
                                // Initialize seasonal pricing if not already done
                                if (!isset($seasonalPricing)) {
                                    include('../includes/algorithms.php');
                                    $seasonalPricing = new SeasonalPricing();
                                }
                                // Calculate seasonal price
                                $today = date('Y-m-d');
                                $seasonalPrice = $seasonalPricing->calculateSeasonalPrice($result->PackagePrice, $result->PackageLocation, $today);
                                $priceDifference = $seasonalPrice - $result->PackagePrice;
                                $hasPriceAdjustment = abs($priceDifference) > 0.01;
                                
                                if($hasPriceAdjustment): ?>
                                    <br><span style="font-size: 0.8rem; color: <?php echo $priceDifference > 0 ? '#DC143C' : '#10b981'; ?>;">
                                        (<?php echo $priceDifference > 0 ? '+' : '-'; ?> Rs. <?php echo number_format(abs($priceDifference)); ?>)
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlentities($result->Creationdate);?></td>
                            <td>
                                <a href="update-package.php?pid=<?php echo htmlentities($result->PackageId);?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                                <a href="manage-packages.php?action=delete&&id=<?php echo $result->PackageId;?>" onclick="return confirm('Do you really want to delete?')" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash me-1"></i>Delete
                                </a>
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
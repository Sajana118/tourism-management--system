<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}
else{
if(isset($_POST['submit']))
{
$pname=$_POST['packagename'];
$ptype=$_POST['packagetype'];	
$plocation=$_POST['packagelocation'];
$pprice=$_POST['packageprice'];	
$pfeatures=$_POST['packagefeatures'];
$pdetails=$_POST['packagedetails'];	
$pimage=$_FILES["packageimage"]["name"];
move_uploaded_file($_FILES["packageimage"]["tmp_name"],"pacakgeimages/".$_FILES["packageimage"]["name"]);
$sql="INSERT INTO tbltourpackages(PackageName,PackageType,PackageLocation,PackagePrice,PackageFetures,PackageDetails,PackageImage) VALUES(:pname,:ptype,:plocation,:pprice,:pfeatures,:pdetails,:pimage)";
$query = $dbh->prepare($sql);
$query->bindParam(':pname',$pname,PDO::PARAM_STR);
$query->bindParam(':ptype',$ptype,PDO::PARAM_STR);
$query->bindParam(':plocation',$plocation,PDO::PARAM_STR);
$query->bindParam(':pprice',$pprice,PDO::PARAM_STR);
$query->bindParam(':pfeatures',$pfeatures,PDO::PARAM_STR);
$query->bindParam(':pdetails',$pdetails,PDO::PARAM_STR);
$query->bindParam(':pimage',$pimage,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
$msg="Package Created Successfully";
}
else 
{
$error="Something went wrong. Please try again";
}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Package | Nepal TMS Admin</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Custom CSS -->
<link href="../assets/css/pages/admin-dashboard.css" rel="stylesheet">
<link href="../assets/css/pages/admin-create-package.css" rel="stylesheet">
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
        <li><a href="create-package.php" class="active"><i class="fas fa-plus-circle"></i> Create Package</a></li>
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
            <h5><i class="fas fa-plus-circle me-2"></i>Create Package</h5>
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
                <li class="breadcrumb-item"><a href="manage-packages.php">Manage Packages</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Package</li>
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
                <h5><i class="fas fa-plus-circle me-2"></i>Create New Tour Package</h5>
            </div>
            <div class="card-body">
                <form class="row g-4" name="package" method="post" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <label for="packagename" class="form-label">Package Name</label>
                        <input type="text" class="form-control" name="packagename" id="packagename" placeholder="Enter package name" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="packagetype" class="form-label">Package Type</label>
                        <select class="form-control" name="packagetype" id="packagetype" required>
                            <option value="">Select Package Type</option>
                            <option value="Adventure Package">Adventure Package</option>
                            <option value="Trekking Package">Trekking Package</option>
                            <option value="Cultural Package">Cultural Package</option>
                            <option value="Wildlife Package">Wildlife Package</option>
                            <option value="Spiritual Package">Spiritual Package</option>
                            <option value="Day Tour Package">Day Tour Package</option>
                            <option value="Scenic Tour Package">Scenic Tour Package</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="packagelocation" class="form-label">Package Location</label>
                        <input type="text" class="form-control" name="packagelocation" id="packagelocation" placeholder="e.g., Kathmandu, Pokhara" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="packageprice" class="form-label">Package Price (Rs)</label>
                        <input type="text" class="form-control" name="packageprice" id="packageprice" placeholder="Enter price in Nepalese Rupees" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="packagefeatures" class="form-label">Package Features</label>
                        <input type="text" class="form-control" name="packagefeatures" id="packagefeatures" placeholder="e.g., Free Pickup-drop facility, Meals included" required>
                    </div>
                    
                    <div class="col-md-12">
                        <label for="packagedetails" class="form-label">Package Details</label>
                        <textarea class="form-control" rows="5" name="packagedetails" id="packagedetails" placeholder="Enter detailed description of the package" required></textarea>
                    </div>
                    
                    <div class="col-md-12">
                        <label for="packageimage" class="form-label">Package Image</label>
                        <input type="file" class="form-control" name="packageimage" id="packageimage" required>
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" name="submit" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i>Create Package
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-redo me-2"></i>Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>
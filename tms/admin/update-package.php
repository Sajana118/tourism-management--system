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
	$packagename=$_POST['packagename'];
	$packagetype=$_POST['packagetype'];
	$packagelocation=$_POST['packagelocation'];
	$packageprice=$_POST['packageprice'];
	$packagefeatures=$_POST['packagefeatures'];
	$packagedetails=$_POST['packagedetails'];	
	$pid=intval($_GET['pid']);

	$sql="update TblTourPackages set PackageName=:packagename,PackageType=:packagetype,PackageLocation=:packagelocation,PackagePrice=:packageprice,PackageFetures=:packagefeatures,PackageDetails=:packagedetails where PackageId=:pid";
	$query = $dbh->prepare($sql);
	$query->bindParam(':packagename',$packagename,PDO::PARAM_STR);
	$query->bindParam(':packagetype',$packagetype,PDO::PARAM_STR);
	$query->bindParam(':packagelocation',$packagelocation,PDO::PARAM_STR);
	$query->bindParam(':packageprice',$packageprice,PDO::PARAM_STR);
	$query->bindParam(':packagefeatures',$packagefeatures,PDO::PARAM_STR);
	$query->bindParam(':packagedetails',$packagedetails,PDO::PARAM_STR);
	$query->bindParam(':pid',$pid,PDO::PARAM_STR);
	$query->execute();
	$msg="Package Updated Successfully";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Package | TMS Admin</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Custom CSS -->
<link href="../assets/css/pages/admin-dashboard.css" rel="stylesheet">
<link href="../assets/css/pages/admin-update-package.css" rel="stylesheet">
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
            <h5><i class="fas fa-edit me-2"></i>Update Package</h5>
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
                <li class="breadcrumb-item active" aria-current="page">Update Package</li>
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
                <h5><i class="fas fa-edit me-2"></i>Update Tour Package</h5>
            </div>
            <div class="card-body">
                <?php 
                $pid=intval($_GET['pid']);
                $sql = "SELECT * from TblTourPackages where PackageId=:pid";
                $query = $dbh -> prepare($sql);
                $query -> bindParam(':pid', $pid, PDO::PARAM_STR);
                $query->execute();
                $results=$query->fetchAll(PDO::FETCH_OBJ);
                $cnt=1;
                if($query->rowCount() > 0)
                {
                foreach($results as $result)
                { ?>
                <form class="row g-4" name="package" method="post" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <label for="packagename" class="form-label">Package Name</label>
                        <input type="text" class="form-control" name="packagename" id="packagename" placeholder="Create Package" value="<?php echo htmlentities($result->PackageName);?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="packagetype" class="form-label">Package Type</label>
                        <select class="form-control" name="packagetype" id="packagetype" required>
                            <option value="">Select Package Type</option>
                            <option value="Adventure Package" <?php if($result->PackageType == 'Adventure Package') echo 'selected'; ?>>Adventure Package</option>
                            <option value="Trekking Package" <?php if($result->PackageType == 'Trekking Package') echo 'selected'; ?>>Trekking Package</option>
                            <option value="Cultural Package" <?php if($result->PackageType == 'Cultural Package') echo 'selected'; ?>>Cultural Package</option>
                            <option value="Wildlife Package" <?php if($result->PackageType == 'Wildlife Package') echo 'selected'; ?>>Wildlife Package</option>
                            <option value="Spiritual Package" <?php if($result->PackageType == 'Spiritual Package') echo 'selected'; ?>>Spiritual Package</option>
                            <option value="Day Tour Package" <?php if($result->PackageType == 'Day Tour Package') echo 'selected'; ?>>Day Tour Package</option>
                            <option value="Scenic Tour Package" <?php if($result->PackageType == 'Scenic Tour Package') echo 'selected'; ?>>Scenic Tour Package</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="packagelocation" class="form-label">Package Location</label>
                        <input type="text" class="form-control" name="packagelocation" id="packagelocation" placeholder=" Package Location" value="<?php echo htmlentities($result->PackageLocation);?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="packageprice" class="form-label">Package Price in NPR</label>
                        <input type="text" class="form-control" name="packageprice" id="packageprice" placeholder=" Package Price in Nepalese Rupees" value="<?php echo htmlentities($result->PackagePrice);?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="packagefeatures" class="form-label">Package Features</label>
                        <input type="text" class="form-control" name="packagefeatures" id="packagefeatures" placeholder="Package Features Eg-free Pickup-drop facility" value="<?php echo htmlentities($result->PackageFetures);?>" required>
                    </div>
                    
                    <div class="col-md-12">
                        <label for="packagedetails" class="form-label">Package Details</label>
                        <textarea class="form-control" rows="5" name="packagedetails" id="packagedetails" placeholder="Package Details" required><?php echo htmlentities($result->PackageDetails);?></textarea>
                    </div>
                    
                    <div class="col-md-12">
                        <label class="form-label">Package Image</label>
                        <div>
                            <img src="pacakgeimages/<?php echo htmlentities($result->PackageImage);?>" width="200">&nbsp;&nbsp;&nbsp;
                            <a href="change-image.php?imgid=<?php echo htmlentities($result->PackageId);?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-image me-1"></i>Change Image
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <label class="form-label">Last Updation Date</label>
                        <div><?php echo htmlentities($result->UpdationDate);?></div>
                    </div>
                    
                    <?php }} ?>
                    
                    <div class="col-12">
                        <button type="submit" name="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Package
                        </button>
                        <a href="manage-packages.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Packages
                        </a>
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
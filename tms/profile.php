<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
	{	
header('location:index.php');
}
else{
if(isset($_POST['submit6']))
	{
$name=$_POST['name'];
$mobileno=$_POST['mobileno'];
$email=$_SESSION['login'];

$sql="update tblusers set FullName=:name,MobileNumber=:mobileno where EmailId=:email";
$query = $dbh->prepare($sql);
$query->bindParam(':name',$name,PDO::PARAM_STR);
$query->bindParam(':mobileno',$mobileno,PDO::PARAM_STR);
$query->bindParam(':email',$email,PDO::PARAM_STR);
$query->execute();
$msg="Profile Updated Successfully";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Profile | Nepal TMS</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- AOS Animation -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="assets/css/pages/profile.css" rel="stylesheet">
</head>
<body>
<?php include('includes/header.php');?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 data-aos="fade-up"><i class="fas fa-user-circle me-3"></i>My Profile</h1>
        <p class="mt-2" data-aos="fade-up" data-aos-delay="100">Manage your personal information</p>
    </div>
</section>

<!-- Profile Section -->
<section class="profile-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if($error){?>
                    <div class="alert alert-danger" data-aos="fade-down">
                        <i class="fas fa-exclamation-circle me-2"></i><strong>ERROR:</strong> <?php echo htmlentities($error); ?>
                    </div>
                <?php } else if($msg){?>
                    <div class="alert alert-success" data-aos="fade-down">
                        <i class="fas fa-check-circle me-2"></i><strong>SUCCESS:</strong> <?php echo htmlentities($msg); ?>
                    </div>
                <?php }?>

                <?php 
                $useremail=$_SESSION['login'];
                $sql = "SELECT * from tblusers where EmailId=:useremail";
                $query = $dbh -> prepare($sql);
                $query -> bindParam(':useremail',$useremail, PDO::PARAM_STR);
                $query->execute();
                $results=$query->fetchAll(PDO::FETCH_OBJ);
                $cnt=1;
                if($query->rowCount() > 0) {
                foreach($results as $result) {	?>

                <div class="profile-card" data-aos="fade-up">
                    <!-- Profile Header -->
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <?php echo strtoupper(substr($result->FullName, 0, 1)); ?>
                        </div>
                        <h2 class="profile-name"><?php echo htmlentities($result->FullName);?></h2>
                        <p class="profile-email"><i class="fas fa-envelope me-2"></i><?php echo htmlentities($result->EmailId);?></p>
                    </div>

                    <!-- Profile Body -->
                    <div class="profile-body">
                        <!-- Stats -->
                        <div class="stats-row">
                            <div class="stat-box">
                                <div class="stat-number">
                                    <?php 
                                    $sql_bookings = "SELECT COUNT(*) as total FROM tblbooking WHERE UserEmail=:email";
                                    $query_bookings = $dbh->prepare($sql_bookings);
                                    $query_bookings->bindParam(':email', $useremail, PDO::PARAM_STR);
                                    $query_bookings->execute();
                                    echo $query_bookings->fetch(PDO::FETCH_OBJ)->total;
                                    ?>
                                </div>
                                <div class="stat-label">Total Bookings</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-number">
                                    <?php 
                                    $sql_issues = "SELECT COUNT(*) as total FROM tblissues WHERE UserEmail=:email";
                                    $query_issues = $dbh->prepare($sql_issues);
                                    $query_issues->bindParam(':email', $useremail, PDO::PARAM_STR);
                                    $query_issues->execute();
                                    echo $query_issues->fetch(PDO::FETCH_OBJ)->total;
                                    ?>
                                </div>
                                <div class="stat-label">Issues Raised</div>
                            </div>
                        </div>

                        <!-- Edit Profile Form -->
                        <form method="post">
                            <h5 class="mb-4" style="color: var(--nepal-blue);"><i class="fas fa-edit me-2"></i>Edit Profile</h5>
                            
                            <div class="mb-4">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user"></i>
                                    Full Name
                                </label>
                                <input type="text" name="name" class="form-control" id="name" value="<?php echo htmlentities($result->FullName);?>" required>
                            </div>

                            <div class="mb-4">
                                <label for="mobileno" class="form-label">
                                    <i class="fas fa-mobile-alt"></i>
                                    Mobile Number
                                </label>
                                <input type="text" class="form-control" name="mobileno" maxlength="10" id="mobileno" value="<?php echo htmlentities($result->MobileNumber);?>" required>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i>
                                    Email Address
                                </label>
                                <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlentities($result->EmailId);?>" readonly>
                            </div>

                            <!-- Account Information -->
                            <h5 class="mb-3 mt-5" style="color: var(--nepal-blue);"><i class="fas fa-info-circle me-2"></i>Account Information</h5>
                            
                            <div class="info-badge">
                                <span class="info-label"><i class="fas fa-calendar-plus me-2"></i>Registration Date</span>
                                <span class="info-value"><?php echo date('d M Y', strtotime($result->RegDate));?></span>
                            </div>

                            <?php if($result->UpdationDate) { ?>
                            <div class="info-badge">
                                <span class="info-label"><i class="fas fa-calendar-check me-2"></i>Last Updated</span>
                                <span class="info-value"><?php echo date('d M Y, h:i A', strtotime($result->UpdationDate));?></span>
                            </div>
                            <?php } ?>

                            <div class="text-center mt-5">
                                <button type="submit" name="submit6" class="btn-update">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php }} ?>

            </div>
        </div>
    </div>
</section>

<?php include('includes/footer.php');?>
<!-- write us -->
<?php include('includes/write-us.php');?>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS Animation -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<!-- Custom JS -->
<script src="assets/js/pages/profile.js"></script>
</body>
</html>
<?php } ?>
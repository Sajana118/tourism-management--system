<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {	
    header('location:index.php');
    exit;
}

$msg = '';
$error = '';

if(isset($_POST['submit'])) {
    $currentPassword = $_POST['password'];
    $newPassword = $_POST['newpassword'];
    $confirmPassword = $_POST['confirmpassword'];
    $username = $_SESSION['alogin'];
    
    // Validate passwords match
    if($newPassword !== $confirmPassword) {
        $error = "New password and confirm password do not match!";
    } else {
        // Get current password from database
        $sql = "SELECT Password FROM admin WHERE UserName=:username";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        
        if($result) {
            $isCurrentPasswordCorrect = false;
            
            // Check if current password is correct (bcrypt or MD5)
            if(password_verify($currentPassword, $result->Password)) {
                $isCurrentPasswordCorrect = true;
            } elseif($result->Password === md5($currentPassword)) {
                $isCurrentPasswordCorrect = true;
            }
            
            if($isCurrentPasswordCorrect) {
                // Update to new bcrypt password
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateSql = "UPDATE admin SET Password=:newpassword WHERE UserName=:username";
                $updateQuery = $dbh->prepare($updateSql);
                $updateQuery->bindParam(':username', $username, PDO::PARAM_STR);
                $updateQuery->bindParam(':newpassword', $newPasswordHash, PDO::PARAM_STR);
                $updateQuery->execute();
                
                $msg = "Your password was successfully changed!";
            } else {
                $error = "Your current password is incorrect!";
            }
        }
    }
}?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Change Password | Nepal TMS Admin</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Custom CSS -->
<link href="../assets/css/pages/admin-dashboard.css" rel="stylesheet">
<link href="../assets/css/pages/admin/change-password.css" rel="stylesheet">
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
        <li><a href="create-package.php"><i class="fas fa-plus-circle"></i> Create Package</a></li>
        <li><a href="manage-bookings.php"><i class="fas fa-calendar-check"></i> Bookings</a></li>
        <li><a href="manage-payments.php"><i class="fas fa-credit-card"></i> Payment Management</a></li>
        <li><a href="profile.php"><i class="fas fa-user-circle"></i> My Profile</a></li>
        <li><a href="change-password.php" class="active"><i class="fas fa-key"></i> Change Password</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</aside>

<!-- Main Content -->
<main class="main-content">
    <!-- Top Bar -->
    <div class="topbar">
        <div class="topbar-left">
            <h5><i class="fas fa-key me-2"></i>Change Password</h5>
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
                <li class="breadcrumb-item active" aria-current="page">Change Password</li>
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
                <h5><i class="fas fa-key me-2"></i>Change Password</h5>
            </div>
            <div class="card-body">
                <form name="chngpwd" method="post" class="row g-4" onSubmit="return valid();">
                    <div class="col-md-12">
                        <label class="form-label">Current Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Enter current password" required="">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-key"></i>
                            </span>
                            <input type="password" class="form-control" name="newpassword" id="newpassword" placeholder="Enter new password" required="">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-key"></i>
                            </span>
                            <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="Confirm new password" required="">
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" name="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Change Password
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
<!-- Custom JS -->
<script src="../js/pages/admin/change-password.js"></script>
</body>
</html>
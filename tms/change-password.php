<?php
session_start();
error_reporting(0);
include('includes/config.php');
require_once('modules/auth/Auth.php');

if(strlen($_SESSION['login'])==0) {	
    header('location:index.php');
    exit;
}

$auth = new Auth($dbh);
$msg = '';
$error = '';

if(isset($_POST['submit_change_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    $email = $_SESSION['login'];
    
    // Validate new password matches confirm
    if($newPassword !== $confirmPassword) {
        $error = "New password and confirm password do not match!";
    } else {
        // Verify current password
        $sql = "SELECT Password FROM tblusers WHERE EmailId=:email LIMIT 1";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_OBJ);
        
        if($user) {
            $isCurrentPasswordCorrect = false;
            
            // Check if it's bcrypt or old MD5
            if(password_verify($currentPassword, $user->Password)) {
                $isCurrentPasswordCorrect = true;
            } elseif($user->Password === md5($currentPassword)) {
                $isCurrentPasswordCorrect = true;
            }
            
            if($isCurrentPasswordCorrect) {
                // Update to new bcrypt password
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateSql = "UPDATE tblusers SET Password=:newpassword WHERE EmailId=:email";
                $updateQuery = $dbh->prepare($updateSql);
                $updateQuery->bindParam(':email', $email, PDO::PARAM_STR);
                $updateQuery->bindParam(':newpassword', $newPasswordHash, PDO::PARAM_STR);
                $updateQuery->execute();
                
                $msg = "Password changed successfully!";
            } else {
                $error = "Current password is incorrect!";
            }
        } else {
            $error = "User not found!";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Change Password | Nepal Tourism</title>
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- AOS Animation -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="assets/css/pages/change-password.css" rel="stylesheet">
</head>
<body>
<?php include('includes/header.php');?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 data-aos="fade-up"><i class="fas fa-key me-3"></i>Change Password</h1>
        <p class="mt-3" style="font-size: 1.1rem;">Update your account password</p>
    </div>
</section>

<!-- Form Section -->
<section class="form-section">
    <div class="container">
        <div class="form-card" data-aos="fade-up">
            <?php if($error) { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><strong>Error!</strong> <?php echo htmlentities($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php } ?>
            <?php if($msg) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><strong>Success!</strong> <?php echo htmlentities($msg); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php } ?>
            
            <form name="change_password_form" method="post" onsubmit="return validatePassword();">
                <div class="mb-4">
                    <label for="current_password" class="form-label">
                        <i class="fas fa-lock me-2"></i>Current Password
                    </label>
                    <input type="password" class="form-control" name="current_password" id="current_password" placeholder="Enter current password" required>
                </div>
                
                <div class="mb-4">
                    <label for="new_password" class="form-label">
                        <i class="fas fa-key me-2"></i>New Password
                    </label>
                    <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Enter new password" required onkeyup="checkPasswordStrength()">
                    <div class="password-strength">
                        <div class="password-strength-bar" id="strength-bar"></div>
                    </div>
                    <small class="text-muted" id="strength-text">Password strength: <span id="strength-level">-</span></small>
                </div>
                
                <div class="mb-4">
                    <label for="confirm_password" class="form-label">
                        <i class="fas fa-check-double me-2"></i>Confirm New Password
                    </label>
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm new password" required>
                </div>
                
                <button type="submit" name="submit_change_password" class="btn btn-change">
                    <i class="fas fa-save me-2"></i>Update Password
                </button>
            </form>
            
            <hr class="my-4">
            <p class="text-center text-muted" style="font-size: 0.9rem; margin: 0;">
                <i class="fas fa-info-circle me-1"></i>Use a strong password with at least 8 characters
            </p>
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
<script src="assets/js/pages/change-password.js"></script>
</body>
</html>
<?php } ?>

<?php
// Login Modal Logic
$loginError = '';
$loginSuccess = '';

if(isset($_POST['signin'])) {
    require_once 'config/database.php';
    require_once 'modules/auth/Auth.php';
    
    $auth = new Auth($dbh);
    $result = $auth->login($_POST['email'], $_POST['password']);
    
    if($result['success']) {
        $loginSuccess = $result['message'];
        // Show welcome popup and redirect
        echo '<script>
            // Show welcome popup
            if (typeof PopupNotification !== "undefined") {
                PopupNotification.success("Welcome back! You have been successfully logged in.");
            } else {
                alert("Welcome back! You have been successfully logged in.");
            }
            
            // Redirect after a short delay
            setTimeout(function() {
                window.location.href = "index.php";
            }, 1500);
        </script>';
        exit();
    } else {
        $loginError = implode('<br>', $result['errors']);
    }
}
?>
<!-- Login Modal CSS -->
<link href="assets/css/components/popup-notifications.css" rel="stylesheet">
<link href="assets/css/pages/login-modal.css" rel="stylesheet">

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Sign In to TMS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if($loginError) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $loginError; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php } ?>
                
                <?php if($loginSuccess) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $loginSuccess; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php } ?>
                
                <form method="post" id="signinForm">
                    <div class="mb-3">
                        <label for="modal-signin-email" class="form-label"><i class="fas fa-envelope me-2"></i>Email Address</label>
                        <input type="email" class="form-control" name="email" id="modal-signin-email" placeholder="Enter your email" autocomplete="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal-signin-password" class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
                        <input type="password" class="form-control" name="password" id="modal-signin-password" placeholder="Enter your password" autocomplete="current-password" required>
                    </div>
                    
                    <div class="mb-3 text-end">
                        <a href="forgot-password.php" class="forgot-link">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" name="signin" class="btn btn-primary">Sign In</button>
                </form>
                
                <div class="text-center mt-3">
                    <small class="text-muted">Don't have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#signupModal" data-bs-dismiss="modal">Sign Up</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popup Notification JS -->
<script src="assets/js/components/popup-notifications.js"></script>
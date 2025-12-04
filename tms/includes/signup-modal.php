<?php
// Signup Modal Logic
$signupError = '';
$signupSuccess = '';

if(isset($_POST['submit'])) {
    require_once 'config/database.php';
    require_once 'modules/auth/Auth.php';
    
    $auth = new Auth($dbh);
    
    $data = [
        'fullname' => $_POST['fname'],
        'mobile' => $_POST['mobilenumber'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'confirm_password' => $_POST['confirm_password'] ?? ''
    ];
    
    // Check if passwords match before calling register
    if ($data['password'] !== $data['confirm_password']) {
        $signupError = "Passwords do not match.";
    } else {
        $result = $auth->register($data);
        
        if($result['success']) {
            $signupSuccess = $result['message'];
            // Show personalized success popup and switch to login modal
            echo '<script>
                // Show personalized success popup using only the popup notification system
                if (typeof PopupNotification !== "undefined") {
                    PopupNotification.success("Welcome ' . addslashes($_POST['fname']) . '! Your account has been created successfully. Please sign in to continue.");
                }
                
                // Hide signup modal and show login modal after a short delay
                setTimeout(function() {
                    var signupModalEl = document.getElementById("signupModal");
                    var loginModalEl = document.getElementById("loginModal");
                    
                    if (signupModalEl) {
                        var signupModal = bootstrap.Modal.getInstance(signupModalEl);
                        if (signupModal) signupModal.hide();
                    }
                    
                    if (loginModalEl) {
                        var loginModal = new bootstrap.Modal(loginModalEl);
                        if (loginModal) loginModal.show();
                    }
                }, 1500);
            </script>';
        } else {
            $signupError = implode('<br>', $result['errors']);
        }
    }
}
?>
<!-- Signup Modal CSS -->
<link href="assets/css/components/popup-notifications.css" rel="stylesheet">
<link href="assets/css/pages/signup-modal.css" rel="stylesheet">

<!-- Signup Modal -->
<div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signupModalLabel">Create TMS Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if($signupError) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $signupError; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php } ?>
                
                <?php if($signupSuccess) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $signupSuccess; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php } ?>
                
                <form name="signup" method="post" id="signupForm">
                    <div class="mb-3">
                        <label for="modal-signup-fname" class="form-label"><i class="fas fa-user me-2"></i>Full Name</label>
                        <input type="text" class="form-control" name="fname" id="modal-signup-fname" placeholder="Enter your full name" autocomplete="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal-signup-mobile" class="form-label"><i class="fas fa-mobile-alt me-2"></i>Mobile Number</label>
                        <input type="text" class="form-control" name="mobilenumber" id="modal-signup-mobile" placeholder="Enter mobile number" maxlength="10" autocomplete="tel" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal-signup-email" class="form-label"><i class="fas fa-envelope me-2"></i>Email Address</label>
                        <input type="email" class="form-control" name="email" id="modal-signup-email" placeholder="Enter your email" autocomplete="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal-signup-password" class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
                        <input type="password" class="form-control" name="password" id="modal-signup-password" placeholder="Create a password (min 6 characters)" autocomplete="new-password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal-signup-confirm-password" class="form-label"><i class="fas fa-lock me-2"></i>Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" id="modal-signup-confirm-password" placeholder="Re-enter password" autocomplete="new-password" required>
                    </div>
                    
                    <button type="submit" name="submit" class="btn btn-primary">Create Account</button>
                </form>
                
                <div class="text-center mt-3">
                    <small class="text-muted">Already have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Sign In</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popup Notification JS -->
<script src="assets/js/components/popup-notifications.js"></script>
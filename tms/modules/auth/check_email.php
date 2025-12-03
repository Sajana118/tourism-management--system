<?php
/**
 * Email Availability Checker (AJAX Endpoint)
 * Modern implementation with proper validation
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/Auth.php';

header('Content-Type: text/html; charset=UTF-8');

if(isset($_POST['email']) && !empty($_POST['email'])) {
    $email = $_POST['email'];
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<span style="color: #dc2626;"><i class="fas fa-times-circle"></i> Invalid email format</span>';
        exit;
    }
    
    // Check availability
    $auth = new Auth($dbh);
    
    if($auth->emailExists($email)) {
        echo '<span style="color: #dc2626;"><i class="fas fa-times-circle"></i> Email already taken</span>';
        echo '<script>$("#submit").prop("disabled", true);</script>';
    } else {
        echo '<span style="color: #10b981;"><i class="fas fa-check-circle"></i> Email available!</span>';
        echo '<script>$("#submit").prop("disabled", false);</script>';
    }
} else {
    echo '<span style="color: #64748b;"><i class="fas fa-info-circle"></i> Enter email to check</span>';
}
?>

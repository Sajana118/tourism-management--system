// Check email availability
function checkAvailability() {
    $("#loaderIcon").show();
    jQuery.ajax({
        url: "modules/auth/check_email.php",
        data: 'email='+$("#signup-email").val(),
        type: "POST",
        success: function(data) {
            $("#user-availability-status").html(data);
            $("#loaderIcon").hide();
        },
        error: function() {
            $("#loaderIcon").hide();
        }
    });
}

// Password strength indicator
function checkPasswordStrength() {
    const password = $("#signup-password").val();
    const strength = $("#password-strength");
    
    if(password.length < 6) {
        strength.html('<span style="color: #dc2626;"><i class="fas fa-times-circle"></i> Weak (min 6 chars)</span>');
    } else if(password.length < 10) {
        strength.html('<span style="color: #f59e0b;"><i class="fas fa-exclamation-circle"></i> Medium</span>');
    } else {
        strength.html('<span style="color: #10b981;"><i class="fas fa-check-circle"></i> Strong</span>');
    }
}

// Handle modal behavior after successful signup
$(document).ready(function() {
    // This function will be called after a successful signup to hide the signup modal
    // and show the signin modal
    if (typeof signupSuccess !== 'undefined' && signupSuccess) {
        setTimeout(function(){ 
            $('#signupModal').modal('hide');
            $('#signinModal').modal('show');
        }, 1000);
    }
});
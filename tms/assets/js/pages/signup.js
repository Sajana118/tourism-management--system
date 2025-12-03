// Signup Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS animations
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });
    
    // Form submission handling
    const signupForm = document.forms.namedItem('signup');
    if (signupForm) {
        signupForm.addEventListener('submit', function(e) {
            // Add loading state to submit button
            const submitBtn = signupForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Account...';
            submitBtn.disabled = true;
            
            // Allow form to submit normally
            // Don't prevent default - let the form submit normally
        });
    }
});
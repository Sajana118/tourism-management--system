// Login Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS animations
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });
    
    // Form submission handling
    const loginForm = document.getElementById('signinForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            // Add loading state to submit button
            const submitBtn = loginForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing In...';
            submitBtn.disabled = true;
            
            // Allow form to submit normally
            // Don't prevent default - let the form submit normally
        });
    }
});
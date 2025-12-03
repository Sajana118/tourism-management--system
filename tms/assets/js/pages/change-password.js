// Initialize AOS animation
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true
});

function validatePassword() {
    const newPass = document.getElementById('new_password').value;
    const confirmPass = document.getElementById('confirm_password').value;
    
    if(newPass !== confirmPass) {
        alert('New password and confirm password do not match!');
        return false;
    }
    
    if(newPass.length < 6) {
        alert('Password must be at least 6 characters long!');
        return false;
    }
    
    return true;
}

function checkPasswordStrength() {
    const password = document.getElementById('new_password').value;
    const strengthBar = document.getElementById('strength-bar');
    const strengthLevel = document.getElementById('strength-level');
    
    let strength = 0;
    if(password.length >= 6) strength++;
    if(password.length >= 8) strength++;
    if(/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if(/[0-9]/.test(password)) strength++;
    if(/[^a-zA-Z0-9]/.test(password)) strength++;
    
    let color = '#e2e8f0';
    let text = 'Too Weak';
    let width = '0%';
    
    if(strength === 1) {
        color = '#ef4444'; text = 'Weak'; width = '25%';
    } else if(strength === 2) {
        color = '#f59e0b'; text = 'Fair'; width = '50%';
    } else if(strength === 3) {
        color = '#eab308'; text = 'Good'; width = '75%';
    } else if(strength >= 4) {
        color = '#22c55e'; text = 'Strong'; width = '100%';
    }
    
    strengthBar.style.backgroundColor = color;
    strengthBar.style.width = width;
    strengthLevel.textContent = text;
    strengthLevel.style.color = color;
}
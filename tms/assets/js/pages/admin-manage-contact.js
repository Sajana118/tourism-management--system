// Toggle submenu
document.querySelectorAll('.sidebar-menu > li').forEach(item => {
    item.addEventListener('click', function(e) {
        if(this.querySelector('ul')) {
            e.preventDefault();
            this.classList.toggle('active');
        }
    });
});

// View enquiry modal
function viewEnquiry(name, email, phone, subject, message) {
    document.getElementById('enquiryName').textContent = name;
    document.getElementById('enquiryEmail').textContent = email;
    document.getElementById('enquiryPhone').textContent = phone;
    document.getElementById('enquirySubject').textContent = subject;
    document.getElementById('enquiryMessage').textContent = message;
    
    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('viewEnquiryModal'));
    modal.show();
}
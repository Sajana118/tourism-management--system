function toggleMobileMenu() {
    const nav = document.querySelector('.main-nav');
    const btn = document.querySelector('.mobile-menu-btn i');
    nav.classList.toggle('active');
    
    if(nav.classList.contains('active')) {
        btn.classList.remove('fa-bars');
        btn.classList.add('fa-times');
    } else {
        btn.classList.remove('fa-times');
        btn.classList.add('fa-bars');
    }
}
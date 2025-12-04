<style>
/* Simple Modern Footer */
.modern-footer {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    color: white;
    padding: 40px 0 20px;
    margin-top: 80px;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

/* Adjust layout when no links are present */
.footer-content:has(.footer-links:empty) {
    justify-content: space-between;
}

.footer-brand {
    font-size: 1.3rem;
    font-weight: 700;
    color: white;
}

.footer-brand .flag {
    font-size: 1.5rem;
}

.footer-links {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 30px;
}

.footer-links a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    transition: color 0.3s;
    font-size: 0.95rem;
}

.footer-links a:hover {
    color: #DC143C;
}

.footer-social {
    display: flex;
    gap: 15px;
}

.social-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s;
}

.social-icon:hover {
    background: #DC143C;
    color: white;
    transform: translateY(-3px);
}

.footer-bottom {
    text-align: center;
    padding-top: 20px;
    font-size: 0.9rem;
    color: rgba(255,255,255,0.7);
}

@media (max-width: 768px) {
    .footer-content {
        flex-direction: column;
        text-align: center;
    }
    
    .footer-links {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<!-- Modern Simple Footer -->
<footer class="modern-footer">
    <div class="container">
        <div class="footer-content">
            <!-- Brand -->
            <div class="footer-brand">
                <span class="flag">🇳🇵</span> Nepal Tourism
            </div>
            
            <!-- Links -->
            <ul class="footer-links">
                <!-- Removed Home, Packages, and About links as per user request -->
            </ul>
            
            <!-- Social -->
            <div class="footer-social">
                <a href="#" class="social-icon" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon" title="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon" title="YouTube"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="footer-bottom">
            <p>© <?php echo date('Y'); ?> Nepal Tourism Management System. All rights reserved.</p>
        </div>
    </div>
</footer>

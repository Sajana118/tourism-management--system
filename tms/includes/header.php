<!-- Header CSS -->
<link href="assets/css/includes/header.css" rel="stylesheet">

<!-- Modern Single Line Header -->
<header class="modern-header">
    <div class="container">
        <div class="header-container">
            <!-- Logo -->
            <a href="index.php" class="logo-brand">
                <span class="logo-flag"><i class="fas fa-mountain"></i></span>
                <span>TMS</span>
            </a>
            
            <!-- Mobile Menu Button -->
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </button>
            
            <!-- Navigation -->
            <nav class="main-nav">
                <ul class="nav-links">
                    <li><a href="index.php"><i class="fas fa-home me-1"></i> Home</a></li>
                    <li><a href="package-list.php"><i class="fas fa-map-marked-alt me-1"></i> Packages</a></li>
                    <li><a href="about-us.php"><i class="fas fa-info-circle me-1"></i> About</a></li>
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#writeusModal"><i class="fas fa-envelope me-1"></i> Contact</a></li>
                </ul>
            </nav>
            
            <!-- User Actions -->
            <div class="header-actions">
                <?php if(isset($_SESSION['login']) && $_SESSION['login']) { ?>
                    <div class="dropdown">
                        <button class="btn-header btn-signin dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> My Account
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="tour-history.php"><i class="fas fa-history me-2"></i>Bookings</a></li>
                            <li><a class="dropdown-item" href="change-password.php"><i class="fas fa-key me-2"></i>Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                <?php } else { ?>
                    <!-- Login and Signup Modals Trigger -->
                    <a href="#" class="btn-header btn-signin" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="fas fa-sign-in-alt me-1"></i> Sign In
                    </a>
                    <a href="#" class="btn-header btn-signup" data-bs-toggle="modal" data-bs-target="#signupModal">
                        <i class="fas fa-user-plus me-1"></i> Sign Up
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</header>

<!-- Header JS -->
<script src="assets/js/includes/header.js"></script>
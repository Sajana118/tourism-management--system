<?php
session_start();
include('includes/config.php');

if(isset($_POST['login'])) {
    $uname = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT UserName, Password FROM admin WHERE UserName=:uname";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uname', $uname, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    
    if($result) {
        // Check if password is bcrypt or MD5
        $isPasswordCorrect = false;
        
        if(password_verify($password, $result->Password)) {
            // Bcrypt password
            $isPasswordCorrect = true;
        } elseif($result->Password === md5($password)) {
            // Old MD5 password - upgrade to bcrypt
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $updateSql = "UPDATE admin SET Password=:password WHERE UserName=:uname";
            $updateQuery = $dbh->prepare($updateSql);
            $updateQuery->bindParam(':password', $newHash, PDO::PARAM_STR);
            $updateQuery->bindParam(':uname', $uname, PDO::PARAM_STR);
            $updateQuery->execute();
            $isPasswordCorrect = true;
        }
        
        if($isPasswordCorrect) {
            $_SESSION['alogin'] = $result->UserName;
            echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
        } else {
            $loginError = true;
        }
    } else {
        $loginError = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | Nepal TMS</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --nepal-red: #DC143C;
    --nepal-blue: #003893;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, var(--nepal-blue) 0%, #1e40af 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.login-container {
    width: 100%;
    max-width: 450px;
}

.login-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    overflow: hidden;
}

.login-header {
    background: linear-gradient(135deg, var(--nepal-red), #b91230);
    color: white;
    padding: 40px 30px;
    text-align: center;
}

.login-header h1 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.login-header p {
    opacity: 0.9;
    font-size: 0.95rem;
}

.nepal-flag {
    font-size: 3rem;
    margin-bottom: 15px;
}

.login-body {
    padding: 40px 30px;
}

.form-label {
    font-weight: 600;
    color: var(--nepal-blue);
    margin-bottom: 10px;
}

.input-group {
    margin-bottom: 25px;
}

.input-group-text {
    background: var(--nepal-blue);
    color: white;
    border: none;
    padding: 12px 15px;
}

.form-control {
    padding: 12px;
    border: 2px solid #e2e8f0;
    border-left: none;
}

.form-control:focus {
    border-color: var(--nepal-red);
    box-shadow: none;
}

.btn-login {
    background: var(--nepal-red);
    color: white;
    padding: 14px;
    border-radius: 10px;
    font-weight: 600;
    border: none;
    width: 100%;
    font-size: 1.1rem;
    transition: all 0.3s;
}

.btn-login:hover {
    background: #b91230;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 20, 60, 0.3);
}

.forgot-link {
    color: var(--nepal-blue);
    text-decoration: none;
    font-size: 0.9rem;
}

.forgot-link:hover {
    color: var(--nepal-red);
}

.back-link {
    text-align: center;
    margin-top: 25px;
}

.back-link a {
    color: white;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.1);
    padding: 10px 20px;
    border-radius: 25px;
    transition: all 0.3s;
}

.back-link a:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-2px);
}

.alert-custom {
    border-radius: 10px;
    padding: 12px 15px;
    margin-bottom: 20px;
}
</style>
</head> 
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
               
                <h1>Admin Portal</h1>
                <p>Nepal Tourism Management System</p>
            </div>
            
            <div class="login-body">
                <?php if(isset($loginError) && $loginError) { ?>
                    <div class="alert alert-danger alert-custom">
                        <i class="fas fa-exclamation-circle me-2"></i>Invalid username or password!
                    </div>
                <?php } ?>
                
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-user me-2"></i>Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="username" class="form-control" placeholder="Enter admin username" required autofocus>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                        </div>
                    </div>
                    
                    <button type="submit" name="login" class="btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Sign In
                    </button>
                </form>
            </div>
        </div>
        
        <div class="back-link">
            <a href="../index.php">
                <i class="fas fa-arrow-left"></i>
                Back to Website
            </a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
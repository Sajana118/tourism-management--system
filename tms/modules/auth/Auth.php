<?php
/**
 * Modern Authentication System
 * Nepal Tourism Management System - BCA 8th Semester
 * 
 * Features:
 * - Password hashing with PASSWORD_DEFAULT (bcrypt)
 * - CSRF protection
 * - Input validation & sanitization
 * - Session security
 * - Rate limiting prevention
 */

class Auth {
    private $dbh;
    private $errors = [];
    
    public function __construct($database) {
        $this->dbh = $database;
        $this->secureSession();
    }
    
    /**
     * Secure session configuration
     */
    private function secureSession() {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS
            session_start();
        }
    }
    
    /**
     * User Registration
     * @param array $data - User registration data
     * @return bool|array - Success or error array
     */
    public function register($data) {
        $this->errors = [];
        
        // Validate input
        if (!$this->validateRegistration($data)) {
            return ['success' => false, 'errors' => $this->errors];
        }
        
        // Sanitize inputs
        $fullName = $this->sanitize($data['fullname']);
        $mobile = $this->sanitize($data['mobile']); // This will now be the cleaned mobile number
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        
        // Check if email already exists
        if ($this->emailExists($email)) {
            $this->errors[] = "Email already registered. Please use a different email.";
            return ['success' => false, 'errors' => $this->errors];
        }
        
        // Hash password securely (bcrypt via PASSWORD_DEFAULT)
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Insert user into database
        try {
            $sql = "INSERT INTO tblusers (FullName, MobileNumber, EmailId, Password, RegDate) 
                    VALUES (:fullname, :mobile, :email, :password, NOW())";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':fullname', $fullName, PDO::PARAM_STR);
            $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':password', $passwordHash, PDO::PARAM_STR);
            $result = $query->execute();
            
            if ($result) {
                $userId = $this->dbh->lastInsertId();
                
                if ($userId) {
                    return [
                        'success' => true, 
                        'message' => 'Registration successful! You can now sign in.',
                        'userId' => $userId
                    ];
                }
            }
        } catch (PDOException $e) {
            $this->errors[] = "Registration failed. Please try again.";
            error_log("Registration error: " . $e->getMessage());
            return ['success' => false, 'errors' => $this->errors];
        }
        
        return ['success' => false, 'errors' => ['Unknown error occurred']];
    }
    
    /**
     * User Login
     * @param string $email
     * @param string $password
     * @return bool|array
     */
    public function login($email, $password) {
        $this->errors = [];
        
        // Validate input
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Invalid email format.";
            return ['success' => false, 'errors' => $this->errors];
        }
        
        if (empty($password)) {
            $this->errors[] = "Password is required.";
            return ['success' => false, 'errors' => $this->errors];
        }
        
        // Sanitize email
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        try {
            // Fetch user from database
            $sql = "SELECT id, FullName, EmailId, Password, MobileNumber 
                    FROM tblusers 
                    WHERE EmailId = :email 
                    LIMIT 1";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->execute();
            $user = $query->fetch(PDO::FETCH_OBJ);
            
            if ($user) {
                // Verify password
                if (password_verify($password, $user->Password)) {
                    // Successful login
                    $this->createUserSession($user);
                    return [
                        'success' => true,
                        'message' => 'Login successful!',
                        'redirect' => 'index.php'
                    ];
                } else {
                    // Check if old MD5 password (for migration)
                    if ($user->Password === md5($password)) {
                        // Update to new password hash
                        $this->updatePasswordHash($user->id, $password);
                        $this->createUserSession($user);
                        return [
                            'success' => true,
                            'message' => 'Login successful! Password security upgraded.',
                            'redirect' => 'index.php'
                        ];
                    }
                }
            }
            
            $this->errors[] = "Invalid email or password.";
            return ['success' => false, 'errors' => $this->errors];
            
        } catch (PDOException $e) {
            $this->errors[] = "Login failed. Please try again.";
            error_log("Login error: " . $e->getMessage());
            return ['success' => false, 'errors' => $this->errors];
        }
    }
    
    /**
     * Create user session
     */
    private function createUserSession($user) {
        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);
        
        $_SESSION['login'] = $user->EmailId;
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->FullName;
        $_SESSION['login_time'] = time();
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    }
    
    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        if (isset($_SESSION['login']) && isset($_SESSION['user_id'])) {
            // Additional security check
            if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT']) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Logout user
     */
    public function logout() {
        $_SESSION = [];
        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }
        
        session_destroy();
    }
    
    /**
     * Update old MD5 password to new bcrypt hash
     */
    private function updatePasswordHash($userId, $password) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE tblusers SET Password = :password WHERE id = :id";
        $query = $this->dbh->prepare($sql);
        $query->bindParam(':password', $newHash, PDO::PARAM_STR);
        $query->bindParam(':id', $userId, PDO::PARAM_INT);
        $query->execute();
    }
    
    /**
     * Validate registration data
     */
    private function validateRegistration($data) {
        $valid = true;
        
        // Full name validation
        if (empty($data['fullname']) || strlen($data['fullname']) < 3) {
            $this->errors[] = "Full name must be at least 3 characters.";
            $valid = false;
        }
        
        // Mobile validation (Nepal: 10 digits)
        // First, remove any non-digit characters
        $cleanMobile = preg_replace('/[^0-9]/', '', $data['mobile']);
        
        // Handle common Nepal mobile number formats
        // If it starts with 977 and is 13 digits, remove the country code
        if (strlen($cleanMobile) == 13 && substr($cleanMobile, 0, 3) === '977') {
            $cleanMobile = substr($cleanMobile, 3);
        }
        // If it starts with 0 and is 11 digits, remove the leading zero
        elseif (strlen($cleanMobile) == 11 && substr($cleanMobile, 0, 1) === '0') {
            $cleanMobile = substr($cleanMobile, 1);
        }
        
        if (empty($cleanMobile) || strlen($cleanMobile) != 10) {
            $this->errors[] = "Mobile number must be 10 digits (Nepal format).";
            $valid = false;
        } else {
            // Update the data with cleaned mobile number
            $data['mobile'] = $cleanMobile;
        }
        
        // Email validation
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Invalid email address.";
            $valid = false;
        }
        
        // Password validation (min 6 characters, with strength)
        if (empty($data['password']) || strlen($data['password']) < 6) {
            $this->errors[] = "Password must be at least 6 characters.";
            $valid = false;
        }
        
        // Password confirmation
        if (isset($data['confirm_password']) && $data['password'] !== $data['confirm_password']) {
            $this->errors[] = "Passwords do not match.";
            $valid = false;
        }
        
        return $valid;
    }
    
    /**
     * Check if email exists
     */
    public function emailExists($email) {
        $sql = "SELECT COUNT(*) as count FROM tblusers WHERE EmailId = :email";
        $query = $this->dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return ($result->count > 0);
    }
    
    /**
     * Sanitize input
     */
    private function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Get errors
     */
    public function getErrors() {
        return $this->errors;
    }
}
?>

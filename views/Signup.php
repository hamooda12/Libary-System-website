<?php session_start(); 


$NotCorrectUser = isset($_SESSION['error']);
$errorMessage = $_SESSION['error'] ?? '';

unset($_SESSION['error']); 
?>
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/signup.css">
</head>
<body>
    <div class="signup-container">
        <div class="signup-header">
            <h2>Create Account</h2>
            <p>Join our community today</p>
        </div>
        <div class="signup-body">
            <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
            <?php endif; ?>
            
            <form id="signupForm" action="../FormValidate/Signupdp.php" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-container">
                        <input type="password" class="form-control" id="password" placeholder="Enter password" required name="password">
                        <span class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <div class="password-strength" id="passwordStrength"></div>
                    <div class="password-requirements">
                        <div class="requirement invalid" id="lengthReq">
                            <i class="fas fa-circle"></i> At least 8 characters
                        </div>
                        <div class="requirement invalid" id="uppercaseReq">
                            <i class="fas fa-circle"></i> At least one uppercase letter
                        </div>
                        <div class="requirement invalid" id="lowercaseReq">
                            <i class="fas fa-circle"></i> At least one lowercase letter
                        </div>
                        <div class="requirement invalid" id="numberReq">
                            <i class="fas fa-circle"></i> At least one number
                        </div>
                        <div class="requirement invalid" id="specialReq">
                            <i class="fas fa-circle"></i> At least one special character
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <div class="password-container">
                        <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm password" required>
                        <span class="password-toggle" id="toggleConfirmPassword">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <div class="text-danger mt-1" id="passwordMatchError" style="display: none;">
                        <i class="fas fa-exclamation-circle me-1"></i> Passwords do not match
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="role" class="form-label">User Type</label>
                    <select class="form-select" id="role" required name="role">
                        <option value="" selected disabled>Select your user type</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                         <option value="staff">Staff</option>
                    </select>
                </div>
                <p style="  color: #c53030;
    padding: 0.875rem 1rem;
   
    margin: 1rem 0;
    font-weight: 500;
    font-size: 0.95rem;
    animation: fadeInUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);"><?php echo htmlspecialchars($errorMessage); ?></p>
                <button type="submit" class="btn btn-signup">Sign Up</button>
                <a href="Login.php"><button type="button" class="btn btn-back" id="backButton">Back to Login</button></a>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="../assets/js/signUp.js"></script>
</body>
</html>
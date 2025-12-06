<?php 

session_start();

$NotCorrectUser = isset($_SESSION['error']);
$errorMessage = $_SESSION['error'] ?? '';

unset($_SESSION['error']); 
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Login | YourApp</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  
</head>
<body>

    <div class="login-container">
        <div class="login-header">
            <h2><i class="fas fa-lock me-2"></i>Welcome Back</h2>
            <p>Sign in to your account to continue</p>
        </div>
        
        <div class="login-body">
            
            <form id="loginForm" action="../FormValidate/logindp.php" method="post">
                <div class="form-floating">
                    <input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
                    <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                </div>
                
                <div class="form-floating password-container">
                 <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
<label for="password"><i class="fas fa-key me-2"></i>Password</label>

<span class="password-toggle" id="togglePassword">
    <i class="fas fa-eye"></i>
</span>

                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">
                        Remember me
                    </label>
                </div>

    <div id="errorPopup" class="error-popup">
  <span id="errorMessage" style="
    color: #c53030;
    padding: 0.875rem 1rem;
   
    margin: 1rem 0;
    font-weight: 500;
    font-size: 0.95rem;
    animation: fadeInUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
   ">
    <?php echo htmlspecialchars($errorMessage); ?>
</span>

</div>


                <button type="submit" class="btn btn-login" id="loginBtn">
                    <i class="fas fa-sign-in-alt me-2"></i>Log In
                </button>
            </form>
            
            <div class="divider">
                <span>or continue with</span>
            </div>
            
            <div class="social-login">
                <a href="#" class="social-btn facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-btn google">
                    <i class="fab fa-google"></i>
                </a>
                <a href="#" class="social-btn twitter">
                    <i class="fab fa-twitter"></i>
                </a>
            </div>
            
            
                <a href="Signup.php" class="signLink"><button class="btn btn-signup" id="signupBtn"><i class="fas fa-user-plus me-2"></i>Create New Account</button></a>
            
            <div class="forgot-password">
                <a href="#">Forgot your password?</a>
            </div>
        </div>
    </div>
 
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
   
</body>
</html>
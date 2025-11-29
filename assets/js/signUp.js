
            const signupForm = document.getElementById('signupForm');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const passwordStrength = document.getElementById('passwordStrength');
            const passwordMatchError = document.getElementById('passwordMatchError');
            const errorAlert = document.getElementById('errorAlert');
           
            const backButton = document.getElementById('backButton');
            
            // Password visibility toggle
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
            
            toggleConfirmPassword.addEventListener('click', function() {
                const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPasswordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
            
            // Password strength checker
            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
                checkPasswordMatch();
            });
            
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);
            
            function checkPasswordStrength(password) {
                let strength = 0;
                
                // Check password requirements
                const hasMinLength = password.length >= 8;
                const hasUpperCase = /[A-Z]/.test(password);
                const hasLowerCase = /[a-z]/.test(password);
                const hasNumbers = /\d/.test(password);
                const hasSpecialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
                
                // Update requirement indicators
                document.getElementById('lengthReq').className = hasMinLength ? 'requirement valid' : 'requirement invalid';
                document.getElementById('uppercaseReq').className = hasUpperCase ? 'requirement valid' : 'requirement invalid';
                document.getElementById('lowercaseReq').className = hasLowerCase ? 'requirement valid' : 'requirement invalid';
                document.getElementById('numberReq').className = hasNumbers ? 'requirement valid' : 'requirement invalid';
                document.getElementById('specialReq').className = hasSpecialChar ? 'requirement valid' : 'requirement invalid';
                
                // Calculate strength
                if (hasMinLength) strength++;
                if (hasUpperCase) strength++;
                if (hasLowerCase) strength++;
                if (hasNumbers) strength++;
                if (hasSpecialChar) strength++;
                
                // Update strength indicator
                passwordStrength.className = 'password-strength';
                if (password.length === 0) {
                    passwordStrength.style.width = '0';
                } else if (strength <= 2) {
                    passwordStrength.className += ' strength-weak';
                } else if (strength <= 4) {
                    passwordStrength.className += ' strength-medium';
                } else {
                    passwordStrength.className += ' strength-strong';
                }
            }
            
            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                if (confirmPassword.length > 0 && password !== confirmPassword) {
                    passwordMatchError.style.display = 'block';
                    confirmPasswordInput.classList.add('is-invalid');
                } else {
                    passwordMatchError.style.display = 'none';
                    confirmPasswordInput.classList.remove('is-invalid');
                }
            }
            
            function validateForm() {
                const username = document.getElementById('username').value;
                const email = document.getElementById('email').value;
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                const role = document.getElementById('role').value;
                
             
                errorAlert.style.display = 'none';
                successAlert.style.display = 'none';
                
                // Check if passwords match
                if (password !== confirmPassword) {
                    showError('Passwords do not match');
                    return false;
                }
                
                // Check password strength
                const hasMinLength = password.length >= 8;
                const hasUpperCase = /[A-Z]/.test(password);
                const hasLowerCase = /[a-z]/.test(password);
                const hasNumbers = /\d/.test(password);
                const hasSpecialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
                
                if (!hasMinLength || !hasUpperCase || !hasLowerCase || !hasNumbers || !hasSpecialChar) {
                    showError('Password does not meet the strength requirements');
                    return false;
                }
                
                // Validate email format
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    showError('Please enter a valid email address');
                    return false;
                }
                
                // Check if role is selected
                if (!role) {
                    showError('Please select a role');
                    return false;
                }
                
                return true;
            }
            
            function showError(message) {
                document.getElementById('errorMessage').textContent = message;
                errorAlert.style.display = 'block';
            }
            
           
            
            // Back button functionality
            backButton.addEventListener('click', function() {
                // In a real application, this would redirect to the login page
                alert('Redirecting to login page...');
                // window.location.href = 'login.html';
            });

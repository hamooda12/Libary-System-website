let passwordInput=document.getElementById("password");
let togglePassword=document.getElementsByClassName("password-toggle");
for (let i = 0; i < togglePassword.length; i++) {
    togglePassword[i].addEventListener("click", function(){
        this.querySelector("i").classList.toggle("fas fa-eye");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
        } else {
            passwordInput.type = "password";
        }       
    });
}
const btnCancelNotCorrectUser = document.getElementById('btnCancelNotCorrectUser');
if (btnCancelNotCorrectUser) {
    btnCancelNotCorrectUser.addEventListener('click', function () {
        document.getElementById('overlayNotCorrectUser').style.display = 'none';
        document.getElementById('modalNotCorrectUser').style.display = 'none';
    });
}
const btnRetryNotCorrectUser = document.getElementById('btnRetryNotCorrectUser');
if (btnRetryNotCorrectUser) {
    btnRetryNotCorrectUser.addEventListener('click', function () {
        document.getElementById('overlayNotCorrectUser').style.display = 'none';
        document.getElementById('modalNotCorrectUser').style.display = 'none';
        window.location.href = '../views/login.php';
        session_destroy();
    });
}       
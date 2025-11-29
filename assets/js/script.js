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
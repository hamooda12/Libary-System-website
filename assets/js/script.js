let passwordInput=document.getElementById("password");
let togglePassword=document.getElementsByClassName("password-toggle");
for (let i = 0; i < togglePassword.length; i++) {
    togglePassword[i].addEventListener("click", function(){
        this.querySelector("i").classList.toggle("fa-eye-slash");
        if (passwordInput.type === "text") {
            passwordInput.type = "password";
        } else {
            passwordInput.type = "text";
        }       
    });
}
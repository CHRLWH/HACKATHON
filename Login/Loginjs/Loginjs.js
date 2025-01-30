const signupButton = document.getElementById("signup-button"),
      loginButton = document.getElementById("login-button"),
      userForms = document.getElementById("user_options-forms"),
      adminLoginButton = document.getElementById("admin-login-button"),
      userLoginButton = document.getElementById("user-login-button"),
      userFormsLogin = document.querySelector(".user_forms-login"),
      userFormsAdmin = document.querySelector(".user_forms-admin"),
      userFormsSignup = document.querySelector(".user_forms-signup")

function showLoginForm() {
    userFormsLogin.style.display = "block"
    userFormsAdmin.style.display = "none"
    userFormsSignup.style.display = "none"
}

function showAdminForm() {
    userFormsLogin.style.display = "none"
    userFormsAdmin.style.display = "block"
    userFormsSignup.style.display = "none"
}

function showSignupForm() {
    userFormsLogin.style.display = "none"
    userFormsAdmin.style.display = "none"
    userFormsSignup.style.display = "block"
}

signupButton.addEventListener("click", () => {
    userForms.classList.remove("bounceRight")
    userForms.classList.add("bounceLeft")
    showSignupForm()
})

loginButton.addEventListener("click", () => {
    userForms.classList.remove("bounceLeft")
    userForms.classList.add("bounceRight")
    showLoginForm()
})

adminLoginButton.addEventListener("click", showAdminForm)
userLoginButton.addEventListener("click", showLoginForm)

const inputUser = document.getElementById("codigo_CAM")
const inputPassword = document.getElementById("correo")

function failLogin() {
    document.getElementById("failMessageLogin").style.display = "block"
}

function unFailLogin() {
    document.getElementById("failMessageLogin").style.display = "none"
}

inputUser.addEventListener("input", unFailLogin)
inputPassword.addEventListener("input", unFailLogin)
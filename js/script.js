//variables de ciblage
const login = document.getElementById("login"),
    password = document.getElementById("password"),
    mail = document.getElementById("mail"),
    fonction = document.getElementById("function"),

    loginError = document.getElementById("loginError"),
    passwordError = document.getElementById("passwordError"),
    mailError = document.getElementById("mailError"),
    fonctionError = document.getElementById("functionError"),

    submit = document.getElementById("submit"),
    reset = document.getElementById("reset"),
    addform = document.getElementById("addform"),
    connectform = document.getElementById("connectform"),

//variables de regex
    loginRegex = /^[a-zA-ZÀ-ÖØ-öø-ÿ\s]{3,30}$/,
    mailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[$@%*+\-_!])[\w$@%*+\-_!]{6,}$/;

function control_field(input, inputError, inputName, regex) {//fonction de contrôle de champ affichage des messages d'erreur et retour de boléen
    if (input.value == "") {
        inputError.innerHTML = "Le champ " + inputName + " est vide"
        return false
    }
    if (!input.value.match(regex)) {
        inputError.innerHTML = "Le champ " + inputName + " ne convient pas"
        return false
    }
    inputError.innerHTML = ""
    return true
}


login.addEventListener("input", function () {//control field sur les différents inputs pour affichage des messages sur login
    login.value = login.value[0].toUpperCase() + login.value.slice(1).toLowerCase();
    control_field(login, loginError, "utilisateur", loginRegex)

})

mail.addEventListener("input", function () {//sur mail
    control_field(mail, mailError, "email", mailRegex)

})

password.addEventListener("input", function () {//sur password
    control_field(password, passwordError, "mot de passe", passwordRegex)

})

fonction.addEventListener("input", function () {//fonction
    control_field(fonction, fonctionError, "fonction", loginRegex)

})

addform.addEventListener("submit", function (e) {//fonction validation formulaire ADD si pas bon Prevent default
    check = control_field(login, loginError, "utilisateur", loginRegex)
    check = check + control_field(mail, mailError, "email", mailRegex)
    check = check + control_field(password, passwordError, "mot de passe", passwordRegex)
    check = check + control_field(fonction, fonctionError, "fonction", loginRegex)
    if (check === false) {
        console.log(check);
        e.preventDefault()
    }
})
connectform.addEventListener("submit", function (e) {//fonction validation formulaire CONNECT si pas bon Prevent default
    check = control_field(login, loginError, "utilisateur", loginRegex)
    check = check + control_field(mail, mailError, "email", mailRegex)
    check = check + control_field(password, passwordError, "mot de passe", passwordRegex)
    if (check === false) {
        console.log(check);
        e.preventDefault()
    }
})


import FormService from "./forms.js";

const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*-=_+;':",./<>?])(?=.{8,})/;

window.addEventListener("load", () => {
    const passwordField = document.querySelector("#password-field");
    const repeatedPasswordField = document.querySelector("#repeated-password-field");
    const formService = new FormService();

    passwordField.addEventListener("blur", () => {
        checkPasswordStrength(formService, passwordField);
        checkPasswordConfirmation(formService, passwordField, repeatedPasswordField);
    });

    repeatedPasswordField.addEventListener("blur", () => {
        checkPasswordConfirmation(formService, passwordField, repeatedPasswordField);
    });
});

function checkPasswordStrength(formService, passwordField) {
    if (isPasswordStrong(passwordField)) {
        formService.hideWarning(passwordField);
    } else {
        formService.showWarning(passwordField, "Słabe hasło!");
    }
}

function isPasswordStrong(passwordField) {
    return passwordRegex.test(passwordField.value);
}

function checkPasswordConfirmation(formService, passwordField, repeatedPasswordField) {
    if (isPasswordConfirmed(passwordField, repeatedPasswordField)) {
        formService.hideWarning(repeatedPasswordField);
    } else {
        formService.showWarning(repeatedPasswordField, "Hasła nie są takie same!");
    }
}

function isPasswordConfirmed(passwordField, repeatedPasswordField) {
    return passwordField.value === repeatedPasswordField.value;
}

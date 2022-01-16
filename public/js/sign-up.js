class PasswordService {
    passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*-=_+;':",./<>?])(?=.{8,})/;

    constructor(passwordField, repeatedPasswordField) {
        this.passwordField = passwordField;
        this.repeatedPasswordField = repeatedPasswordField;
    }

    isPasswordStrong() {
        return this.passwordRegex.test(this.passwordField.value);
    }

    isPasswordConfirmed() {
        return this.passwordField.value === this.repeatedPasswordField.value;
    }
}

class FieldService {
    constructor(field) {
        this.field = field;
        this.prompt = document.createElement("span");
    }

    showPrompt(text) {
        this.prompt.textContent = text;
        this.field.after(this.prompt);
    }

    hidePrompt() {
        this.prompt.remove();
    }
}

window.addEventListener("load", () => {
    const passwordField = document.querySelector("#password-field");
    const repeatedPasswordField = document.querySelector("#repeated-password-field");
    const confirmButton = document.querySelector("form button");

    const passwordFieldService = new FieldService(passwordField);
    const repeatedPasswordFieldService = new FieldService(repeatedPasswordField);
    const passwordService = new PasswordService(passwordField, repeatedPasswordField);

    passwordField.addEventListener("blur", () => {
        const isPasswordStrong = checkPasswordStrength(passwordService, passwordFieldService);
        const isPasswordConfirmed = checkPasswordConfirmation(passwordService, repeatedPasswordFieldService);

        confirmButton.disable = isPasswordStrong && isPasswordConfirmed;
    });

    repeatedPasswordField.addEventListener("blur", () => {
        const isPasswordConfirmed = checkPasswordConfirmation(passwordService, repeatedPasswordFieldService);

        confirmButton.disable = isPasswordConfirmed;
    });
});

function checkPasswordStrength(passwordService, passwordFieldService) {
    const isPasswordStrong = passwordService.isPasswordStrong();

    if (isPasswordStrong) {
        passwordFieldService.hidePrompt();
    } else {
        passwordFieldService.showPrompt("Słabe hasło!");
    }

    return isPasswordStrong;
}

function checkPasswordConfirmation(passwordService, repeatedPasswordFieldService) {
    const isPasswordConfirmed = passwordService.isPasswordConfirmed();

    if (isPasswordConfirmed) {
        repeatedPasswordFieldService.hidePrompt();
    } else {
        repeatedPasswordFieldService.showPrompt("Hasła nie są takie same!");
    }

    return isPasswordConfirmed;
}

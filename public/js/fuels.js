import FormService from "./forms.js";

window.addEventListener("load", () => {
    bindFuelFieldsValidation();
});

function bindFuelFieldsValidation() {
    const formService = new FormService();
    const fuelFields = document.querySelectorAll("input[type='text']");

    fuelFields.forEach(field => bindFuelFieldValidation(formService, field));
}

function bindFuelFieldValidation(formService, field) {
    field.addEventListener("blur", () => {
        if (isContentValid(field)) {
            formService.hideWarning(field);
        } else {
            formService.showWarning(field, "Podaj prawidłową liczbę dodatnią!");
        }
    });
}

function isContentValid(field) {
    const value = parseFloat(field.value);
    const isDecimal = !isNaN(value) && isFinite(field.value);

    return isDecimal && value >= 0;
}

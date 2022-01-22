export default class FormService {
    constructor() {
        this.formButtonService = new FormButtonService();
        this.formWarningsService = new FormWarningsService();
    }

    showWarning(field, warning) {
        if (this.formWarningsService.isWarningBeingShownForField(field)) {
            this.formWarningsService.updateForField(field, warning);
        } else {
            this.formWarningsService.showForField(field, warning);
        }

        this.formButtonService.setDisabled(true);
    }

    hideWarning(field) {
        if (this.formWarningsService.isWarningBeingShownForField(field)) {
            this.formWarningsService.hideForField(field);
            this.formButtonService.setDisabled(this.formWarningsService.isAnyWarningBeingShown());
        }
    }
}

class FormWarningsService {
    constructor() {
        this.warnings = {};
    }

    updateForField(field, warning) {
        this.warnings[field.id].textContent = warning;
    }

    showForField(field, warning) {
        const warningElement = document.createElement("span");

        this.warnings[field.id] = warningElement;
        this.warnings[field.id].textContent = warning;

        field.after(warningElement);
    }

    hideForField(field) {
        this.warnings[field.id].remove();
        delete this.warnings[field.id];
    }

    isWarningBeingShownForField(field) {
        return field.id in this.warnings;
    }

    isAnyWarningBeingShown() {
        return this.warnings.size > 0;
    }
}

class FormButtonService {
    constructor() {
        this.confirmButton = document.querySelector("form button, button[form]");
    }

    setDisabled(value) {
        this.confirmButton.disabled = value;
    }
}

function isValidYear(value) {
    const integerValue = parseInt(value);

    return integerValue > 1950 && integerValue < 3000;
}

function isPercentDecimal(value) {
    return isPositiveDecimal(value) && parseFloat(value) <= 100;
}

function isPositiveDecimal(value) {
    const floatValue = parseFloat(value);
    const isValidDecimal = !isNaN(floatValue) && isFinite(value);

    return isValidDecimal && floatValue >= 0;
}

window.addEventListener("load", () => {
    const createHeater = document.querySelector("#create-heater");

    createHeater.addEventListener("click", () => {
        createHeater.disabled = true;
        createHeater.textContent = "Czekaj...";

        fetch("/heaters", {method: "POST"})
            .then(() => window.location.reload());
    });
});

window.addEventListener("load", () => {
    bindCreateHeaterAction();
});

function bindCreateHeaterAction() {
    const createHeaterButton = document.querySelector("#create-heater");

    createHeaterButton.addEventListener("click", () => {
        createHeaterButton.disabled = true;
        createHeaterButton.textContent = "Czekaj...";

        fetch("/heaters", {method: "POST"})
            .then(() => window.location.reload());
    });
}

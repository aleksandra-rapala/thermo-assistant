window.addEventListener("load", () => {
    markCurrentEndpointButtonAsActive();
});

function markCurrentEndpointButtonAsActive() {
    document.querySelector("a[href='" + window.location.pathname + "'] button").className = "active";
}

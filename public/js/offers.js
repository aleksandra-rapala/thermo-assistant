window.addEventListener("load", () => {
    showSubscriptions();

    document
        .querySelectorAll("input[type='checkbox'][name='offers']")
        .forEach(bindActions);
})

function showSubscriptions() {
    fetch("/offers")
        .then(response => response.json())
        .then(statuses => statuses.forEach(showSubscription))
}

function showSubscription(subscription) {
    document.querySelector("input[type='checkbox'][name='offers'][value='" + subscription + "']").checked = true;
}

function bindActions(checkbox) {
    checkbox.addEventListener("change", event => {
        checkbox.checked? subscribe(checkbox.value) : unsubscribe(checkbox.value);
    });
}

function subscribe(subscriptionName) {
    fetch("/offers?subscription-name=" + subscriptionName + "&active=1", {method: "POST"})
        .then(() => {});
}

function unsubscribe(subscriptionName) {
    fetch("/offers?subscription-name=" + subscriptionName + "&active=0", {method: "POST"})
        .then(() => {});
}

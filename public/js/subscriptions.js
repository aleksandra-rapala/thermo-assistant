window.addEventListener("load", () => {
    showSubscriptions();

    document
        .querySelectorAll("input[type='checkbox'][name='subscriptions']")
        .forEach(bindActions);
})

function showSubscriptions() {
    fetch("/subscriptions")
        .then(response => response.json())
        .then(statuses => statuses.forEach(showSubscription))
}

function showSubscription(subscription) {
    document.querySelector("input[type='checkbox'][name='subscriptions'][value='" + subscription + "']").checked = true;
}

function bindActions(checkbox) {
    checkbox.addEventListener("change", () => {
        checkbox.checked? subscribe(checkbox.value) : unsubscribe(checkbox.value);
    });
}

function subscribe(subscriptionName) {
    fetch("/subscriptions?name=" + subscriptionName + "&active=1", {method: "POST"})
        .then(() => {});
}

function unsubscribe(subscriptionName) {
    fetch("/subscriptions?name=" + subscriptionName + "&active=0", {method: "POST"})
        .then(() => {});
}

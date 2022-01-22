window.addEventListener("load", () => {
    fetch("/pollutions")
        .then(response => response.json())
        .then(pollutionsResult => showInPollutionsTable(pollutionsResult))
});

function showInPollutionsTable(pollutionsResult) {
    const pollutionsTable = document.querySelector("#pollutions-table");
    const pollutionsTableCaption = pollutionsTable.querySelector("caption");

    pollutionsResult.substances.forEach(substance => {
        const tableRow = buildTableRow(substance);
        pollutionsTable.appendChild(tableRow);
    });

    pollutionsTableCaption.innerText = pollutionsResult.summary;
}

function buildTableRow(substance) {
    const substanceRow = document.createElement("tr");
    const labelCell = document.createElement("td");
    const valueCell = document.createElement("td");

    labelCell.innerText = substance.label;
    valueCell.innerText = (substance.value / 1000000).toFixed(4);

    substanceRow.appendChild(labelCell);
    substanceRow.appendChild(valueCell);

    return substanceRow;
}

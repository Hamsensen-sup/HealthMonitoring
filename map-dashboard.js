function submitPurokForm() {
    document.getElementById('purokForm').submit();
}

function submitYearForm() {
    document.getElementById('yearForm').submit();
}

function toggleDisplay() {
    const display = document.getElementById('advisoryDisplay');
    const editArea = document.getElementById('advisoryEdit');
    const buttonLabel = document.getElementById('buttonLabel');
    const returnButton = document.getElementById('returnButton');

    display.style.display = 'block';
    editArea.style.display = 'none';
    buttonLabel.innerText = 'Edit';
    returnButton.style.display = 'none';
}

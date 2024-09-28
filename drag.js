let isDragging = false;
let startX, startY, currentX = 0, currentY = 0;

const draggableContainer = document.getElementById('draggableContainer');
const draggableImage = draggableContainer.querySelector('img');

// Initialize transform if it doesn't have a value
if (!draggableImage.style.transform) {
    draggableImage.style.transform = `translate(0px, 0px)`;
}

draggableContainer.addEventListener('mousedown', function (e) {
    e.preventDefault();
    isDragging = true;
    startX = e.pageX - currentX;
    startY = e.pageY - currentY;
    draggableContainer.style.cursor = 'grabbing';
});

window.addEventListener('mouseup', function () {
    isDragging = false;
    draggableContainer.style.cursor = 'grab';
    const matches = draggableImage.style.transform.match(/translate\(([^,]+)px,\s?([^)]+)px\)/);
    if (matches) {
        currentX = parseInt(matches[1], 10);
        currentY = parseInt(matches[2], 10);
    }
});

window.addEventListener('mousemove', function (e) {
    if (!isDragging) return;
    e.preventDefault();

    let x = e.pageX - startX;
    let y = e.pageY - startY;

    // Calculate bounds
    const maxX = 0;
    const maxY = 0;
    const minX = draggableContainer.clientWidth - draggableImage.width;
    const minY = draggableContainer.clientHeight - draggableImage.height;

    // Constrain x and y
    if (x > maxX) x = maxX;
    if (x < minX) x = minX;
    if (y > maxY) y = maxY;
    if (y < minY) y = minY;

    draggableImage.style.transform = `translate(${x}px, ${y}px)`;
});

// Recalculate constraints on window resize
window.addEventListener('resize', function () {
    const maxX = 0;
    const maxY = 0;
    const minX = draggableContainer.clientWidth - draggableImage.width;
    const minY = draggableContainer.clientHeight - draggableImage.height;
    let x = currentX;
    let y = currentY;

    if (x > maxX) x = maxX;
    if (x < minX) x = minX;
    if (y > maxY) y = maxY;
    if (y < minY) y = minY;

    draggableImage.style.transform = `translate(${x}px, ${y}px)`;
});

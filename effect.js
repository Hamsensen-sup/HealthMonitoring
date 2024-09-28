window.onload = function() {
    // get all containers
    const containers = document.querySelectorAll('.container, .admin-content, .record-content, .record-form, .announcement-content, .cases-content, .container, .map-wrapper, .profile-margin, .map-container, .guest-content, .form-container');

    // iterate over each container and apply the effect
    containers.forEach(container => {
        setTimeout(() => {
            container.style.opacity = "1";
        }, 100);
    });
};

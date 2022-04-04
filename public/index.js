// Click events for the info buttons
const infoBtns = document.querySelectorAll('.js-video-info');
infoBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
        // Start an ajax request to fetch the xml data from the server
        const id = btn.dataset.id;
        let xhr = new XMLHttpRequest();
        xhr.open('GET', '/video/info?id=' + id);
        xhr.send();
        xhr.onload = () => {
            // Escape the xml
            let xml = xhr.responseText;
            xml = xml.replace(/\</g, "&lt;").replace(/\>/g, "&gt;");

            // Reset the dialog content
            const content = document.getElementById('dialogContent');
            content.innerHTML = '';

            // Create pre element and add the xml to it
            const pre = document.createElement('pre');
            pre.setAttribute('lang', 'xml');
            pre.innerHTML = xml;
            content.appendChild(pre);

            // Show the dialog
            const dialog = document.getElementById('xmlDialog');
            dialog.showModal();
        };
        xhr.onerror = () => {alert('An error occurred! Please try again.')};
    });
});

// Click event to show the actual video
const showBtns = document.querySelectorAll('.js-video-show');
showBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
        const dialog = document.getElementById('videoDialog');

        // Set the dialog heading
        const heading = document.getElementById('videoTitle');
        heading.innerText = btn.dataset.title;

        // Set the dialog content
        const content = document.getElementById('videoContent');
        let element;
        if (btn.dataset.isVideo) {
            element = document.createElement('video');
            element.setAttribute('src', '/video/stream?id=' + btn.dataset.id);
            element.setAttribute('autoplay', 'autoplay');
            element.setAttribute('controls', 'controls');
            element.setAttribute('width', '600px');
        } else {
            element = document.createElement('img');
            element.setAttribute('src', '/video/stream?id=' + btn.dataset.id);
            element.setAttribute('alt', btn.dataset.title);
        }

        // Append the source element to the dialog
        content.innerHTML = '';
        content.appendChild(element);
        dialog.showModal();
    });
});

// Close a given dialog
document.querySelectorAll('.dialogClose').forEach((btn) => {
    btn.addEventListener('click', () => {
        const dialog = document.getElementById(btn.dataset.dialog);
        dialog.close();
    });
});
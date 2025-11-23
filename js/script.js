// Notecore JavaScript

/**
 * Displays a success modal with a custom message.
 * @param {string} message The message to display in the modal.
 * @param {string} [redirectUrl] Optional URL to redirect to when "Done" is clicked. If not provided, the modal just closes.
 */
function showSuccessModal(message, redirectUrl) {
    console.log("showSuccessModal called with message:", message, "redirectUrl:", redirectUrl);
    const overlay = document.getElementById('success-modal-overlay');
    const messageEl = document.getElementById('success-modal-message');
    const doneBtn = document.getElementById('success-modal-done');

    console.log("Overlay element:", overlay);
    console.log("Message element:", messageEl);
    console.log("Done button element:", doneBtn);

    if (overlay && messageEl && doneBtn) {
        messageEl.textContent = message;
        overlay.style.display = 'flex';
        console.log("Modal display set to flex.");

        const doneClickHandler = () => {
            console.log("Done button clicked.");
            overlay.style.display = 'none';
            if (redirectUrl) {
                window.location.href = redirectUrl;
            }
            // Remove the event listener to prevent multiple bindings
            doneBtn.removeEventListener('click', doneClickHandler);
        };

        doneBtn.addEventListener('click', doneClickHandler);
    } else {
        console.error("One or more modal elements not found!");
    }
}
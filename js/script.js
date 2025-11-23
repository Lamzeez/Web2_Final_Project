// Notecore JavaScript

/**
 * Displays a success modal with a custom message.
 * @param {string} message The message to display in the modal.
 * @param {string} [redirectUrl] Optional URL to redirect to when "Done" is clicked. If not provided, the modal just closes.
 */
function showSuccessModal(message, redirectUrl) {
    const overlay = document.getElementById('success-modal-overlay');
    const messageEl = document.getElementById('success-modal-message');
    const doneBtn = document.getElementById('success-modal-done');

    if (overlay && messageEl && doneBtn) {
        messageEl.textContent = message;
        overlay.style.display = 'flex';

        const doneClickHandler = () => {
            overlay.style.display = 'none';
            if (redirectUrl) {
                window.location.href = redirectUrl;
            }
            // Remove the event listener to prevent multiple bindings
            doneBtn.removeEventListener('click', doneClickHandler);
        };

        doneBtn.addEventListener('click', doneClickHandler);
    }
}
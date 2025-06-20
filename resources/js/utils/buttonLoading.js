// Utility for toggling loading state on buttons

/**
 * Toggles loading state on a button element.
 * @param {HTMLButtonElement} button - The button to toggle.
 * @param {boolean} isLoading - Whether to enable loading state.
 */
export function setButtonLoading(button, isLoading = true) {
    if (!button) return;
    if (isLoading) {
        button.setAttribute('data-loading', '');
        button.disabled = true;
    } else {
        button.removeAttribute('data-loading');
        button.disabled = false;
    }
}

/**
 * Attach a click listener to trigger loading state for a duration.
 * @param {string} buttonId - The ID of the button to initialize.
 * @param {number} loadingDuration - Duration in ms to keep loading state.
 */
export function initButtonLoading(buttonId, loadingDuration = 3000) {
    const button = document.getElementById(buttonId);
    if (!button) return;
    button.addEventListener('click', () => {
        setButtonLoading(button, true);
        setTimeout(() => {
            setButtonLoading(button, false);
        }, loadingDuration);
    });
}

export default setButtonLoading; 
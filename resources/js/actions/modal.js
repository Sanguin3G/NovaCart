// Delegated handler for opening <dialog> modals
document.addEventListener('click', (event) => {
    const btn = event.target.closest('[data-modal-open]');
    if (!btn) return;
    event.preventDefault();

    const id = btn.getAttribute('data-modal-open');
    const dialog = document.getElementById(id);
    if (dialog && typeof dialog.showModal === 'function') {
        dialog.showModal();
    }
});

// Close modal when clicking on backdrop, and auto-open on error states
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('dialog').forEach(dialog => {
        // Close by clicking outside
        dialog.addEventListener('click', (e) => {
            const rect = dialog.getBoundingClientRect();
            if (
                e.clientX < rect.left ||
                e.clientX > rect.right ||
                e.clientY < rect.top ||
                e.clientY > rect.bottom
            ) {
                dialog.close();
            }
        });
    });
}); 
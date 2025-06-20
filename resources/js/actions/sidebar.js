// Sidebar toggle and backdrop handler
document.addEventListener('DOMContentLoaded', () => {
    // Toggle sidebar visibility
    document.querySelectorAll('[data-sidebar-toggle]').forEach(btn => {
        btn.addEventListener('click', () => {
            document.body.toggleAttribute('data-show-stashed-sidebar');
        });
    });

    // Close sidebar when clicking on backdrop
    document.querySelectorAll('[data-sidebar-backdrop]').forEach(backdrop => {
        backdrop.addEventListener('click', () => {
            document.body.removeAttribute('data-show-stashed-sidebar');
        });
    });
}); 
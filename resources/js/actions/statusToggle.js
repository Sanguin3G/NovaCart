import { api } from '../utils/api';
import { Toast } from '../utils/toast';

// Delegated handler for status toggle checkboxes
document.addEventListener('change', async (event) => {
    const toggle = event.target.closest('.js-status-toggle');
    if (!toggle) return;

    const url = toggle.dataset.url;
    if (!url) return;

    // Keep previous state to revert on error
    const prev = !toggle.checked;

    try {
        const res = await api.patch(url);
        Toast.success(res.message || 'Status updated successfully.');
        const refreshTarget = toggle.closest('[id$="-table"]');
        if (refreshTarget && window.htmx) window.htmx.trigger(refreshTarget, 'load');
    } catch (err) {
        Toast.error(err.message || 'Failed to update status.');
        toggle.checked = prev;
    }
});

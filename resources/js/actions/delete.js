import { api } from '../utils/api';
import { Toast, Confirm } from '../utils/toast';

// Delegated handler for delete buttons
document.addEventListener('click', async (event) => {
    const btn = event.target.closest('.js-delete-btn');
    if (!btn) return;
    event.preventDefault();

    const url = btn.dataset.url;
    if (!url) return;

    // Prevent deletion if flagged
    if (btn.hasAttribute('data-prevent-delete')) {
        Toast.error('Cannot delete this item.');
        return;
    }

    try {
        const result = await Confirm.delete();
        if (!result.isConfirmed) return;
        const res = await api.delete(url);
        Toast.success(res.message);
        // Remove the row if inside a table
        const row = btn.closest('tr');
        if (row) row.remove();
    } catch (err) {
        Toast.error(err.message || 'Failed to delete the item.');
    }
}); 
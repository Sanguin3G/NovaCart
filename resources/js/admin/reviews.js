import { api } from '../utils/api.js';
import { Toast } from '../utils/toast.js';

const refresh = () => {
    const target = document.getElementById('reviews-table');
    if (target && window.htmx) window.htmx.trigger(target, 'load');
};

document.addEventListener('click', async (event) => {
    const detail = event.target.closest('.js-review-detail');
    if (detail) {
        const modal = document.getElementById('review-detail-modal');
        const content = document.getElementById('review-detail-content');
        if (!modal || !content) return;

        modal.classList.add('is-open');
        content.innerHTML = '<div class="nc-state"><span class="animate-pulse text-sm text-gray-500">Loading review…</span></div>';
        if (window.htmx) await window.htmx.ajax('GET', detail.dataset.url, {target: '#review-detail-content', swap: 'innerHTML'});
        return;
    }

    const toggle = event.target.closest('.js-review-toggle');
    if (!toggle || toggle.disabled) return;

    toggle.disabled = true;
    try {
        const response = await api.patch(toggle.dataset.url);
        Toast.success(response.message || 'Review status updated.');
        refresh();
    } catch (error) {
        Toast.error(error.message || 'Could not update the review.');
        toggle.disabled = false;
    }
});

import { Toast } from '../utils/toast.js';
import { api } from '../utils/api.js';

const reload = (selector, url) => window.htmx?.ajax('GET', url, {target: selector, swap: 'innerHTML'});

document.addEventListener('click', (event) => {
    const tab = event.target.closest('.tab-link');
    if (tab) {
        const name = tab.dataset.tab;
        document.querySelectorAll('.tab-link').forEach((button) => {
            button.classList.toggle('border-orange-500', button === tab);
            button.classList.toggle('text-orange-600', button === tab);
            button.classList.toggle('border-transparent', button !== tab);
        });
        document.querySelectorAll('[id^="tab-"]').forEach((pane) => pane.classList.toggle('hidden', pane.id !== 'tab-' + name));
        const pane = document.getElementById('tab-' + name);
        if (pane && pane.dataset.loaded !== 'true') {
            pane.dataset.loaded = 'true';
            window.htmx?.trigger(pane, 'load');
        }
    }

    const reviewButton = event.target.closest('.write-review-btn, .edit-review-btn');
    if (reviewButton) {
        const modal = document.getElementById('review-modal');
        modal?.classList.remove('hidden');
        modal?.classList.add('flex');
        document.getElementById('review-modal-title').textContent = reviewButton.dataset.productName || '';
        document.getElementById('product-id-hidden').value = reviewButton.dataset.productId;
        document.getElementById('review-body').value = reviewButton.dataset.body || '';
        document.getElementById('review-rating').value = reviewButton.dataset.rating || '0';
        highlight(Number(reviewButton.dataset.rating || 0));
    }
});

document.getElementById('reviews-search')?.addEventListener('input', (event) => {
    const value = encodeURIComponent(event.target.value);
    reload('#pending-table', '/my/reviews/pending/data?search=' + value);
    if (!document.getElementById('tab-mine')?.classList.contains('hidden')) {
        reload('#mine-table', '/my/reviews/mine/data?search=' + value);
    }
});

let currentRating = 0;
function highlight(value) {
    currentRating = Number(value);
    document.querySelectorAll('#star-widget .star').forEach((star) => {
        star.classList.toggle('text-yellow-400', Number(star.dataset.value) <= currentRating);
    });
}
document.addEventListener('click', (event) => {
    const star = event.target.closest('#star-widget .star');
    if (star) {
        highlight(star.dataset.value);
        document.getElementById('review-rating').value = currentRating;
    }
});

document.getElementById('review-form')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    if (!currentRating) return Toast.info('Pick a rating first.');
    const productId = document.getElementById('product-id-hidden').value;
    try {
        await api.post('/products/' + productId + '/reviews', new FormData(event.currentTarget));
        Toast.success('Review saved.');
        document.getElementById('review-modal').classList.add('hidden');
        reload('#pending-table', '/my/reviews/pending/data');
        reload('#mine-table', '/my/reviews/mine/data');
    } catch (error) {
        Toast.error(error.message || 'Could not save the review.');
    }
});

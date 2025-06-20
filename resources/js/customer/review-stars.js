import {Toast} from '../utils/toast.js';
import {api} from '../utils/api.js';

document.addEventListener('DOMContentLoaded', () => {
    const starWidget = document.getElementById('star-widget');
    const writeBtn = document.getElementById('write-review-btn');
    const reviewModal = document.getElementById('review-modal');
    const reviewForm = document.getElementById('review-form');
    const ratingInput = document.getElementById('review-rating');

    if (!starWidget) return;

    let currentRating = parseInt(starWidget.dataset.currentRating || '0', 10);

    if (currentRating > 0) {
        highlight(currentRating);
    }

    const stars = starWidget.querySelectorAll('.star');
    stars.forEach(star => {
        star.addEventListener('mouseenter', () => highlight(star.dataset.value));
        star.addEventListener('mouseleave', () => highlight(currentRating));
        star.addEventListener('click', () => {
            currentRating = star.dataset.value;
            ratingInput.value = currentRating;
            highlight(currentRating);
            // Reveal the "Write review" button once a rating is selected
            if (writeBtn && writeBtn.classList.contains('hidden')) {
                writeBtn.classList.remove('hidden');
            }
        });
    });

    function highlight(val) {
        stars.forEach(s => {
            if (s.dataset.value <= val) {
                s.classList.add('text-yellow-400');
                s.classList.remove('text-gray-300', 'dark:text-gray-600');
            } else {
                s.classList.remove('text-yellow-400');
                s.classList.add('text-gray-300', 'dark:text-gray-600');
            }
        });
    }

    // open modal (simple Tailwind: just remove `hidden`)
    if (writeBtn) {
        writeBtn.addEventListener('click', () => {
            // Pre-fill form when updating
            const bodyField = document.getElementById('review-body');
            if (starWidget.dataset.currentBody) {
                bodyField.value = starWidget.dataset.currentBody;
            }
            ratingInput.value = currentRating;
            highlight(currentRating);
            reviewModal?.classList.remove('hidden');
            reviewModal?.classList.add('flex');
        });
    }

    // submit review
    reviewForm?.addEventListener('submit', async e => {
        e.preventDefault();
        if (currentRating === 0) {
            Toast.info('Please select a star rating');
            return;
        }
        const url = starWidget.dataset.productReviewUrl;
        const formData = new FormData(reviewForm);
        formData.set('rating', currentRating);
        try {
            const result = await api.post(url, formData);
            Toast.success('Review submitted!');
            // Update average rating text on the fly (no full reload)
            const avgText = document.getElementById('avg-rating-text');
            if (avgText && result.average_rating) {
                avgText.textContent = `${result.average_rating.toFixed(1)}/5`;
            }
            starWidget.dataset.currentRating = currentRating;
            starWidget.dataset.currentBody = formData.get('body');
            highlight(currentRating);
            // Disable further interaction on product page
            starWidget.classList.add('pointer-events-none');
            writeBtn.classList.add('hidden');
            reviewModal.classList.add('hidden');
            reviewModal.classList.remove('flex');
        } catch (err) {
            console.error(err);
            if (err.status === 422 && err.errors) {
                const messages = Object.values(err.errors).flat().join('\n');
                Toast.info(messages);
            } else {
                Toast.error('Error submitting review');
            }
        }
    });
});

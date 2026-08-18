document.addEventListener('click', (event) => {
    const btn = event.target.closest('[data-modal-open]');
    if (btn) {
        event.preventDefault();
        const modal = document.getElementById(btn.dataset.modalOpen);
        modal?.classList.add('is-open');
        modal?.classList.remove('hidden');
        modal?.querySelector('input, textarea, select')?.focus({preventScroll: true});
    }

    const close = event.target.closest('[data-modal-close]');
    if (close) {
        close.closest('.nc-modal')?.classList.remove('is-open');
    }

    if (event.target.classList.contains('nc-modal')) {
        event.target.classList.remove('is-open');
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        document.querySelectorAll('.nc-modal.is-open').forEach((modal) => modal.classList.remove('is-open'));
    }
});

export function renderPagination(container, meta, onPageChange) {
    if (!container) return;

    // Clear if no pagination needed
    if (!meta || !Array.isArray(meta.links) || meta.last_page <= 1) {
        container.innerHTML = '';
        return;
    }

    container.innerHTML = meta.links.map(link => {
        const label = link.label
            .replace(/&laquo;/g, '«')
            .replace(/&raquo;/g, '»')
            .trim();
        const page = link.url ? new URL(link.url).searchParams.get('page') : null;
        const classes = [
            'pagination-link',
            'inline-flex', 'items-center', 'px-3', 'py-1', 'text-sm', 'border', 'rounded',
            link.active ? 'bg-orange-600 text-white' : 'text-gray-700 dark:text-gray-300',
            !page ? 'disabled cursor-not-allowed opacity-50' : ''
        ].join(' ');
        return `<a href="#" data-page="${page ?? ''}" class="${classes}">${label}</a>`;
    }).join(' ');

    // Attach one-time listeners
    container.querySelectorAll('.pagination-link').forEach(el => {
        el.addEventListener('click', e => {
            e.preventDefault();
            if (el.classList.contains('disabled')) return;
            const page = el.dataset.page;
            if (page && typeof onPageChange === 'function') {
                onPageChange(parseInt(page, 10), el);
            }
        }, {once: true});
    });
}

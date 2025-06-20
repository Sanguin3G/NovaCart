export function toggleListLoading(spinnerEl, listEl, isLoading) {
    if (!spinnerEl || !listEl) return;
    spinnerEl.style.display = isLoading ? 'block' : 'none';
    listEl.style.display = isLoading ? 'none' : '';
}

/**
 * Toggle loading state inside a section based on a data attribute for spinners.
 * @param {HTMLElement} sectionEl - The section element that contains elements with data-loading="spinner".
 * @param {boolean} isLoading
 */
export function toggleSectionLoading(sectionEl, isLoading) {
    if (!sectionEl) return;
    sectionEl.querySelectorAll('[data-loading="spinner"], [data-loading="skeleton"]')
        .forEach(el => el.classList.toggle('hidden', !isLoading));
} 
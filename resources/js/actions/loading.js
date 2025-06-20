// Toggle loading overlay on API events
document.addEventListener('loading-start', () => {
    const overlay = document.getElementById('loading-overlay');
    if (!overlay) return;
    // Prepare for fade in
    overlay.classList.remove('invisible', 'pointer-events-none', 'opacity-0');
    // Trigger transition to opacity-100
    requestAnimationFrame(() => {
        overlay.classList.add('opacity-100');
    });
});

document.addEventListener('loading-end', () => {
    const overlay = document.getElementById('loading-overlay');
    if (!overlay) return;
    // Fade out
    overlay.classList.remove('opacity-100');
    overlay.classList.add('opacity-0');
    // After fade, hide interactions
    overlay.addEventListener('transitionend', () => {
        overlay.classList.add('invisible', 'pointer-events-none');
    }, { once: true });
}); 
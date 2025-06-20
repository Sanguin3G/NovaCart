// Vanilla JS popover handler
document.addEventListener('DOMContentLoaded', () => {
    const popovers = [];

    // Helper to close with animation
    const close = (m) => {
        m.classList.add('opacity-0', 'scale-95');
        m.classList.remove('opacity-100', 'scale-100');
        // Wait for transition then hide
        m.addEventListener('transitionend', () => m.classList.add('invisible'), {once: true});
    };

    // Helper to open with animation
    const open = (m) => {
        m.classList.remove('invisible', 'opacity-0', 'scale-95');
        m.classList.add('opacity-100', 'scale-100');
    };

    document.querySelectorAll('.js-popover').forEach(wrapper => {
        const trigger = wrapper.querySelector('.js-popover-trigger');
        const menu = wrapper.querySelector('.js-popover-menu');
        if (!trigger || !menu) return;
        popovers.push(menu);

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            // Close other popovers
            popovers.forEach(m => {
                if (m !== menu && !m.classList.contains('invisible')) close(m);
            });
            // Toggle this menu
            if (menu.classList.contains('invisible')) {
                open(menu);
            } else {
                close(menu);
            }
        });
    });

    // Close popovers on outside click
    document.addEventListener('click', () => {
        popovers.forEach(m => {
            if (!m.classList.contains('invisible')) close(m);
        });
    });

    // Close popovers on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            popovers.forEach(m => {
                if (!m.classList.contains('invisible')) close(m);
            });
        }
    });
});

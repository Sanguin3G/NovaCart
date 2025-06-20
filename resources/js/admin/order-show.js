// Initialize order-show functionality
function initOrderShow() {
    console.log('initOrderShow running');

    // Handle auto-dismissing alerts
    const alertBox = document.getElementById('order-success-alert');
    if (alertBox) {
        console.log('Found alert box');
        setTimeout(() => {
            alertBox.remove();
        }, 5000);
    }

    // Handle order actions
    const actionsContainer = document.getElementById('order-actions-container');
    console.log('Actions container:', actionsContainer);

    if (actionsContainer) {
        const orderId = actionsContainer.dataset.orderId;
        console.log('Order ID:', orderId);

        const actionButtons = actionsContainer.querySelectorAll('.action-btn');
        console.log('Found action buttons:', actionButtons);
        actionButtons.forEach(btn => {
            console.log('Button:', btn, 'Action:', btn.dataset.action);
        });

        actionsContainer.addEventListener('click', (e) => {
            console.log('Container clicked', e);
            const button = e.target.closest('.action-btn');
            console.log('Clicked button:', button);

            if (!button) {
                console.log('No action button found in click path');
                return;
            }

            const action = button.dataset.action;
            console.log('Button action:', action);

            if (!action) {
                console.log('No action defined on button');
                return;
            }

            console.log('Processing action:', action, 'for order:', orderId);

            const onSuccess = () => {
                console.log('Action successful, reloading page...');
                window.location.reload();
            };

            if (action === 'cancelled') {
                console.log('Calling adminCancelOrder');
                adminCancelOrder(orderId, onSuccess);
            } else {
                console.log('Calling adminUpdateStatus with action:', action);
                adminUpdateStatus(orderId, action, onSuccess);
            }
        });
    } else {
        console.log('No actions container found');
    }
}

// Run on DOM ready or immediately if already ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initOrderShow);
} else {
    initOrderShow();
}

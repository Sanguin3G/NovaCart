document.addEventListener('DOMContentLoaded', () => {
    const alertBox = document.getElementById('order-success-alert');
    if (alertBox) {
        setTimeout(() => {
            alertBox.remove();
        }, 5000);
    }
});

// Handle cancel order action on details page using reusable helper
const cancelBtn = document.getElementById('cancel-order-btn');
if (cancelBtn) {
    cancelBtn.addEventListener('click', () => {
        const orderId = cancelBtn.dataset.id;
        cancelOrder(orderId, () => window.location.href = '/orders');
    });
}

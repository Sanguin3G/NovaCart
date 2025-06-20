import { Confirm, Toast } from './toast.js';
import { api } from './api.js';

/**
 * Cancel an order at a given endpoint.
 * @param {string} url  Full URL (relative) to POST for cancellation
 * @param {Function} [onSuccess]
 */
export function cancelOrder(url, onSuccess = () => {}) {
    Confirm.delete({
        title: 'Cancel Order',
        text: 'Are you sure you want to cancel this order?',
        confirmText: 'Yes, cancel',
    }).then((result) => {
        if (!result.isConfirmed) return;

        api.post(url, {})
            .then((data) => {
                Toast.success(data.message || 'Done');
                onSuccess();
            })
            .catch((err) => Toast.error(err.message || 'Error'));
    });
}

/**
 * Update order status.
 * @param {string} url  PATCH URL
 * @param {string} status  New status value
 * @param {Function} [onSuccess]
 */
export function updateStatus(url, status, onSuccess = () => {}) {
    Confirm.confirm({
        title: 'Update Status',
        text: `Change status to "${status}"?`,
        confirmText: 'Update',
        icon: 'info',
    }).then((result) => {
        if (!result.isConfirmed) return;

        api.patch(url, { status })
            .then((data) => {
                Toast.success(data.message || 'Status updated');
                onSuccess();
            })
            .catch((err) => Toast.error(err.message || 'Error'));
    });
} 
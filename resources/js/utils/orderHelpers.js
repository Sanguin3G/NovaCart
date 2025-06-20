import { Confirm, Toast } from './toast.js';

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

        const token = document.querySelector('meta[name="csrf-token"]')?.content || '';

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify({}),
        })
            .then(async (resp) => {
                if (!resp.ok) {
                    const txt = await resp.text();
                    throw new Error(txt || 'Request failed');
                }
                return resp.json().catch(() => ({ message: 'Success' }));
            })
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

        const token = document.querySelector('meta[name="csrf-token"]')?.content || '';

        fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify({ status }),
        })
            .then(async (resp) => {
                if (!resp.ok) {
                    const txt = await resp.text();
                    throw new Error(txt || 'Request failed');
                }
                return resp.json().catch(() => ({ message: 'Status updated' }));
            })
            .then((data) => {
                Toast.success(data.message || 'Status updated');
                onSuccess();
            })
            .catch((err) => Toast.error(err.message || 'Error'));
    });
} 
import {cancelOrder, updateStatus} from '../utils/orderHelpers.js';

/**
 * Update order status via admin endpoint.
 * @param {number|string} id
 * @param {string} status
 * @param {string} status
 * @param {Function} [onSuccess]
 */
export function adminUpdateStatus(id, status, onSuccess = () => {
}) {
    updateStatus(`/admin/orders/${id}/status`, status, onSuccess);
}

/**
 * Cancel an order via admin endpoint.
 * @param {number|string} id
 * @param {Function} [onSuccess]
 */
export function adminCancelOrder(id, onSuccess = () => {
}) {
    cancelOrder(`/admin/orders/${id}/cancel`, onSuccess);
}

window.adminUpdateStatus = adminUpdateStatus;
window.adminCancelOrder = adminCancelOrder;

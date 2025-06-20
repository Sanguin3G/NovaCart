import {cancelOrder} from '../utils/orderHelpers.js';

/**
 * Prompt user then cancel an order.
 *
 * @param {number|string} orderId  ID of the order to cancel
 * @param {Function} [onSuccess]  Optional callback executed after successful cancel
 */
export function cancelOrderCustomer(orderId, onSuccess = () => {
}) {
    cancelOrder(`/orders/${orderId}/cancel`, onSuccess);
}

window.cancelOrder = cancelOrderCustomer;

<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Exception;

class OrderController extends Controller
{
    /**
     * Display a listing of the customer's orders.
     */
    public function index(): View
    {
        return view('customer.orders.index');
    }

    /**
     * Get order data for DataTables.
     * @throws Exception
     */
    public function getData(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            return (new Order())->getCustomerOrderData($request);
        }
        abort(403, 'Direct access not allowed.');
    }

    /**
     * Display the specified order.
     */
    public function show(Request $request, Order $order)
    {
        if (Auth::id() !== $order->user_id) {
            abort(403, 'You are not authorized to view this order.');
        }

        $order->load(['orderItems.product', 'user']);

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Cancel a pending order (customer action).
     */
    public function cancel(Request $request, Order $order): JsonResponse
    {
        // Ensure the authenticated user owns the order
        if ($order->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        // Only pending orders can be cancelled
        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Only pending orders can be cancelled.'], 422);
        }

        $order->status = 'cancelled';
        $order->save();

        return response()->json(['message' => 'Order has been cancelled successfully.']);
    }
} 
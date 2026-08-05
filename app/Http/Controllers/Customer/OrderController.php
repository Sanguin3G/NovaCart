<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the customer's orders.
     */
    public function index(): View
    {
        return view('customer.orders.htmx-index');
    }

    public function getData(Request $request): View
    {
        $query = Order::where('user_id', Auth::id())
            ->withCount('orderItems')
            ->latest();
        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $query->where('status', $request->status_filter);
        }
        if ($search = $request->input('search')) {
            $query->where('order_number', 'like', "%{$search}%");
        }
        $orders = $query->paginate(10)->withQueryString();
        return view('customer.orders.partials.table', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Request $request, Order $order): View
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

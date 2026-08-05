<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a specific order with items & customer info.
     */
    public function show(Order $order): View
    {
        $order->load(['orderItems.product', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order status (admin action).
     */
    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $order->status = $request->status;
        $order->save();

        return response()->json(['message' => 'Order status updated successfully.']);
    }

    /**
     * Cancel an order (admin override).
     */
    public function cancel(Order $order): JsonResponse
    {
        // Only pending orders can be cancelled
        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Only pending orders can be cancelled.'], 422);
        }

        $order->status = 'cancelled';
        $order->save();

        return response()->json(['message' => 'Order has been cancelled successfully.']);
    }

    /**
     * Display list of orders for admins.
     */
    public function index(): View
    {
        return view('admin.orders.htmx-index');
    }

    public function getData(Request $request): View
    {
        $query = Order::with('user')->withCount('orderItems')->latest();
        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $query->where('status', $request->status_filter);
        }
        if ($search = $request->input('search')) {
            $query->where('order_number', 'like', "%{$search}%");
        }
        $orders = $query->paginate(10)->withQueryString();
        return view('partials.admin_orders_table', compact('orders'));
    }
}

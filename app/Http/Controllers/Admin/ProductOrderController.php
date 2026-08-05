<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductOrderController extends Controller
{
    public function index(Product $product, Request $request): View
    {
        $query = Order::with(['user:id,name,email'])
            ->withCount('orderItems')
            ->whereHas('orderItems', fn ($items) => $items->where('product_id', $product->id))
            ->latest();

        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $query->where('status', $request->status_filter);
        }
        if ($search = $request->input('search')) {
            $query->where('order_number', 'like', "%{$search}%");
        }

        $orders = $query->paginate(10)->withQueryString();
        return view('admin.products.orders', compact('product', 'orders'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductOrderController extends Controller
{
    /**
     * Handle the incoming request: return orders that include the given product.
     * Supports both GET and POST so it can be wired as a DataTables server-side source.
     *
     * @throws Exception
     */
    public function getOrdersForProduct(Product $product, Request $request): JsonResponse
    {
        // TODO: consider adding authorization logic if a policy is available
        $statusFilter = $request->input('status_filter', 'all');
        $searchValue = $request->input('search_value');

        // Base query: orders that have items for this product
        $query = Order::with(['user:id,name,email'])
            ->withCount('orderItems')
            ->whereHas('orderItems', function ($q) use ($product) {
                $q->where('product_id', $product->id);
            })
            ->select('orders.*');

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }
        
        if (!empty($searchValue)) {
            $query->where('order_number', 'like', '%' . $searchValue . '%');
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('order_number', function ($order) {
                return '<a href="' . route('admin.orders.show', $order) . '" class="text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-500">#' . $order->order_number . '</a>';
            })
            ->addColumn('customer', fn($order) => e($order->user->name))
            ->addColumn('items_count', fn($order) => $order->order_items_count)
            ->addColumn('total_amount', fn($order) => '$' . number_format($order->total_amount, 2))
            ->addColumn('created_at', function ($order) {
                return $order->created_at->format('M d, Y');
            })
            ->addColumn('status', function ($order) {
                $statusClasses = [
                    'completed' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400',
                    'pending' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-400',
                    'processing' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400',
                    'cancelled' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400',
                    'shipped' => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-400',
                ];
                $defaultClass = 'bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-400';
                $class = $statusClasses[$order->status] ?? $defaultClass;

                return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . $class . '">' . ucfirst($order->status) . '</span>';
            })
            ->addColumn('actions', function ($order) {
                $detailUrl = route('admin.orders.show', $order); // we will create this route later
                return '<a href="' . $detailUrl . '" class="view-order-btn text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-500">Detail</a>';
            })
            ->rawColumns(['status', 'actions', 'order_number'])
            ->make();
    }
}

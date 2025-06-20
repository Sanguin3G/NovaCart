<?php

namespace App\Models;

use App\Libraries\Common;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'order_number',
        'shipping_address',
        'billing_address',
        'payment_method',
        'payment_status',
        'notes',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            // If order_number is not provided, generate one
            if (empty($order->order_number)) {
                // Generate a unique order number with prefix 'ORD' followed by random string
                $order->order_number = 'ORD' . strtoupper(Str::random(6));
            }
        });
    }

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for the order.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get customer order data for DataTables (wrapper around shared builder).
     */
    public function getCustomerOrderData(Request $request): JsonResponse
    {
        $query = $this->where('user_id', Auth::id())
            ->select('orders.*')
            ->withCount('orderItems');

        return $this->prepareOrderDataTable($request, $query, false);
    }

    /**
     * Get admin order data for DataTables (wrapper around shared builder).
     */
    public function getAdminOrderData(Request $request): JsonResponse
    {
        $query = $this->with('user')
            ->select('orders.*')
            ->withCount('orderItems');

        return $this->prepareOrderDataTable($request, $query, true);
    }

    /**
     * Shared DataTable builder for customer & admin views.
     */
    private function prepareOrderDataTable(Request $request, $query, bool $admin): JsonResponse
    {
        // Common filters
        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $query->where('status', $request->status_filter);
        }

        if ($search = $request->input('search_value')) {
            $query->where('order_number', 'like', "%$search%");
        }

        $dt = DataTables::of($query);

        // Index
        $dt->addIndexColumn();

        // Customer column for admin only
        if ($admin) {
            $dt->addColumn('customer', fn($o) => e($o->user->name));
        }

        // Items count column
        $dt->addColumn('items_count', fn($o) => $o->order_items_count);

        // Payment method (both views)
        $dt->editColumn('payment_method', fn($o) => ucwords(str_replace('_', ' ', $o->payment_method)));

        // Order number link differs
        $dt->editColumn('order_number', function ($o) use ($admin) {
            $url = $admin ? route('admin.orders.show', $o) : route('orders.show', $o);
            $class = $admin
                ? 'text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-500'
                : 'text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-500';
            return '<a href="' . $url . '" class="' . $class . '">#' . $o->order_number . '</a>';
        });

        // Created at formatting (customer view only)
        if ($admin) {
            $dt->editColumn('created_at', fn($o) => $o->created_at->format('M d, Y'));
        } else {
            $dt->editColumn('created_at', fn($o) => $o->created_at->format('M d, Y'));
        }

        // Status badge using Blade component
        $dt->editColumn('status', fn($o) => view('components.order-status', ['status' => $o->status])->render());

        // Shipping address (customer only)
        if (!$admin) {
            $dt->editColumn('shipping_address', function ($o) {
                return '<span title="' . e($o->shipping_address) . '">' . e(Str::limit($o->shipping_address, 35)) . '</span>';
            });
        }

        // Total amount (both views)
        $dt->editColumn('total_amount', fn($o) => '$' . number_format($o->total_amount, 2));

        // Actions column
        if ($admin) {
            $dt->addColumn('actions', function ($o) {
                $detailUrl = route('admin.orders.show', $o);
                return '<a href="' . $detailUrl . '" class="view-order-btn text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-500">Detail</a>';
            });
        } else {
            $dt->addColumn('actions', fn($o) => $this->customerActions($o));
        }

        // Raw columns
        $rawCols = ['status', 'actions', 'order_number'];
        if (!$admin) {
            $rawCols[] = 'shipping_address';
        }
        $dt->rawColumns($rawCols);

        return $dt->make();
    }

    /**
     * Generate action buttons for customer table rows.
     */
    private function customerActions(Order $order): string
    {
        $viewBtn = '<a href="' . route('orders.show', $order) . '" class="view-order-btn mr-2">View</a>';

        if ($order->status === 'pending') {
            $cancelBtn = '<button data-id="' . $order->id . '" class="cancel-order-btn bg-red-600 hover:bg-red-700 text-white rounded-full px-3 py-1 text-xs font-medium">Cancel</button>';
            return $viewBtn . $cancelBtn;
        }

        return $viewBtn;
    }
}

?>

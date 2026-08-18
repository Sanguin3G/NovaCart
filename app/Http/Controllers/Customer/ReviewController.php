<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        return view('customer.reviews.htmx-index');
    }

    // Data for products needing review

    /**
     * @throws Exception
     */
    public function pendingData(Request $request): View
    {
        $userId = Auth::id();

        $reviewed = ProductReview::where('user_id', $userId)->pluck('product_id');

        $query = Product::whereIn('id', static function ($q) use ($userId) {
            $q->select('product_id')
                ->from('order_items')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->where('orders.user_id', $userId)
                ->whereIn('orders.status', ['completed', 'delivered']);
        })
            ->whereNotIn('id', $reviewed);

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%$search%");
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        return view('partials.customer_pending_reviews_table', compact('products'));
    }

    // Data for user reviews

    /**
     * @throws Exception
     */
    public function mineData(Request $request): View
    {
        $query = ProductReview::with('product')
            ->where('user_id', Auth::id())
            ->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('body', 'like', "%$search%")
                    ->orWhereHas('product', fn($p) => $p->where('name', 'like', "%$search%"));
            });
        }

        $reviews = $query->paginate(10)->withQueryString();
        return view('partials.customer_mine_reviews_table', compact('reviews'));
    }
}

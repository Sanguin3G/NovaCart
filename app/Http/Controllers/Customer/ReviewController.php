<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return view('customer.reviews.index');
    }

    // Data for products needing review
    public function pendingData(Request $request): JsonResponse
    {
        $userId = Auth::id();

        $reviewed = ProductReview::where('user_id', $userId)->pluck('product_id');

        $query = Product::whereIn('id', static function ($q) use ($userId) {
            $q->select('product_id')
                ->from('order_items')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->where('orders.user_id', $userId)
                ->where('orders.status', 'completed');
        })
            ->whereNotIn('id', $reviewed);

        if ($search = $request->input('search_value')) {
            $query->where('name', 'like', "%$search%");
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('image', fn($p) => '<img src="' . e($p->image_url) . '" class="h-12">')
            ->addColumn('actions', fn($p) => '<button class="write-review-btn text-orange-600 hover:underline" data-product-id="' . $p->id . '" data-product-name="' . e($p->name) . '">' . __('Write Review') . '</button>')
            ->rawColumns(['image', 'actions'])
            ->make();
    }

    // Data for user reviews
    public function mineData(Request $request): JsonResponse
    {
        $query = ProductReview::with('product')
            ->where('user_id', Auth::id())
            ->latest();

        if ($search = $request->input('search_value')) {
            $query->where(function($q) use ($search){
                $q->where('body', 'like', "%$search%")
                  ->orWhereHas('product', fn($p)=>$p->where('name','like',"%$search%"));
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('image', fn($r) => '<img src="' . e($r->product->image_url) . '" class="h-12">')
            ->addColumn('product', fn($r) => e($r->product->name))
            ->editColumn('rating', fn($r) => str_repeat('★', $r->rating))
            ->addColumn('actions', fn($r) => '<button class="edit-review-btn text-blue-600 hover:underline" data-review-id="' . $r->id . '" data-product-id="' . $r->product_id . '" data-rating="' . $r->rating . '" data-body="' . e($r->body) . '" data-product-name="' . e($r->product->name) . '">' . __('Edit') . '</button>')
            ->rawColumns(['image', 'rating', 'actions'])
            ->make();
    }
}

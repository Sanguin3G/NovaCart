<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductReviewController extends Controller
{
    /**
     * Store a newly created review (AJAX).
     */
    public function store(StoreReviewRequest $request, Product $product): JsonResponse
    {
        $data = $request->validated();

        // Determine reviewer name
        $name = Auth::check() ? Auth::user()->name : ($data['reviewer_name'] ?? 'Guest');

        $review = null;
        if (Auth::check()) {
            $existing = ProductReview::where('product_id', $product->id)
                ->where('user_id', Auth::id())
                ->first();

            if ($existing) {
                $existing->update([
                    'rating' => $data['rating'],
                    'body' => $data['body'],
                ]);
                $review = $existing->fresh();
            }
        }

        if (!$review) {
            $review = ProductReview::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'reviewer_name' => $name,
                'rating' => $data['rating'],
                'body' => $data['body'],
            ]);
        }

        return response()->json([
            'message' => 'Thank you for your review!',
            'average_rating' => $product->averageRating(),
            'review' => $review,
        ]);
    }

    /**
     * Show the "to-review" page – products bought by user but not yet reviewed.
     * @throws Exception
     */
    public function todo(Request $request)
    {
        if ($request->header('HX-Request')) {
            $user = Auth::user();

            // Fetch products from completed orders of user, not yet reviewed
            $subQuery = DB::table('product_reviews')
                ->select('product_id')
                ->whereNull('deleted_at')
                ->where('user_id', $user->id);

            $products = Product::query()
                ->whereIn('id', function ($q) use ($user) {
                    $q->select('product_id')
                        ->from('order_items')
                        ->join('orders', 'orders.id', '=', 'order_items.order_id')
                        ->where('orders.user_id', $user->id)
                        ->where('orders.status', 'completed');
                })
                ->whereNotIn('id', $subQuery);

            $products = $products->latest()->paginate(10)->withQueryString();
            return view('partials.customer_todo_reviews_table', compact('products'));
        }

        return view('customer.reviews.htmx-todo');
    }

    public function list(Product $product, Request $request): JsonResponse
    {
        $perPage = 5;
        $reviews = $product->reviews()
            ->latest()
            ->paginate($perPage);

        return response()->json($reviews);
    }
}

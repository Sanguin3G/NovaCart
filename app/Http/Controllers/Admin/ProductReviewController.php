<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductReviewController extends Controller
{
    public function data(Request $request): View
    {
        // Authorization is handled by the `auth:admin` middleware on the route group.

        $query = ProductReview::with(['product', 'user'])
            ->withTrashed();

        // Apply status filter (active / disabled)
        $status = $request->input('status_filter');
        if ($status === 'active') {
            $query->whereNull('deleted_at');
        } elseif ($status === 'disabled') {
            $query->whereNotNull('deleted_at');
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('body', 'like', "%{$search}%")
                    ->orWhereHas('product', fn ($product) => $product->where('name', 'like', "%{$search}%"));
            });
        }
        $reviews = $query->latest()->paginate(10)->withQueryString();
        return view('partials.admin_reviews_table', compact('reviews'));
    }

    /**
     * Show review details (modal friendly HTML).
     */
    public function show(ProductReview $review): View
    {
        // Authorization is handled by the `auth:admin` middleware on the route group.

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Disable (soft-delete) or enable a review.
     */
    public function disable(ProductReview $review): JsonResponse
    {
        // Authorization is handled by the `auth:admin` middleware on the route group.

        if ($review->trashed()) {
            $review->restore();
            $state = 'enabled';
        } else {
            $review->delete();
            $state = 'disabled';
        }

        return response()->json(['message' => "Review $state"]);
    }

    public function index(): View
    {
        // Authorization is handled by the `auth:admin` middleware on the route group.
        return view('admin.reviews.htmx-index');
    }
}

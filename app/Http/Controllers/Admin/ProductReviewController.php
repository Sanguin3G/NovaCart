<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductReviewController extends Controller
{
    /**
     * DataTables JSON with all reviews (including disabled).
     */
    public function data(Request $request): JsonResponse
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

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('product', fn($r) => e($r->product->name))
            ->addColumn('reviewer', function ($r) {
                return $r->user ? e($r->user->name) : e($r->reviewer_name);
            })
            ->editColumn('rating', fn($r) => str_repeat('★', $r->rating))
            ->addColumn('status', function ($r) {
                return view('components.status-toggle', [
                    'url'     => route('admin.reviews.disable', $r),
                    'checked' => ! $r->trashed(),
                ])->render();
            })
            ->addColumn('actions', function ($r) {
                $showUrl   = route('admin.reviews.show', $r);
                $detailBtn = '<button type="button" data-url="'.$showUrl.'" class="view-btn bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">'.__('Detail').'</button>';

                return $detailBtn;
            })
            ->orderColumn('status', function($query, $order) {
                // NULL deleted_at means active, so order by active first when ascending
                return $query->orderBy('deleted_at', $order);
            })
            ->rawColumns(['status', 'actions'])
            ->make();
    }

    /**
     * Show review details (modal friendly HTML).
     */
    public function show(ProductReview $review)
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

        return response()->json([ 'message' => "Review $state" ]);
    }

    public function index()
    {
        // Authorization is handled by the `auth:admin` middleware on the route group.
        return view('admin.reviews.index');
    }
} 
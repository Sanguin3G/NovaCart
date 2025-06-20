<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderItem;

class ProductController extends Controller
{
    /**
     * Display a listing of the products for customers.
     */
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->query('search', '');
        $categoryFilter = $request->query('category', '');
        $sortField = $request->query('sort_by', 'name');
        $sortDirection = $request->query('direction', 'asc');

        $validSortFields = ['name', 'price', 'created_at'];
        if (!in_array($sortField, $validSortFields, true)) {
            $sortField = 'name';
        }
        if (!in_array(strtolower($sortDirection), ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $productsQuery = Product::with('category')
            ->where('is_active', true)
            ->when($search, fn($query, $search) => $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            }))
            ->when($categoryFilter, fn($query, $categoryId) => $query->where('category_id', $categoryId))
            ->orderBy($sortField, $sortDirection);

        $products = $productsQuery->paginate(12)->withQueryString();

        $categories = Cache::remember('categories.all_sorted_by_name', now()->addHours(24), static function () {
            return Category::orderBy('name')->get();
        });

        if ($request->ajax()) {
            return ProductResource::collection($products)
                ->additional(['categories' => $categories])
                ->response();
        }

        // Initial page load: just render the view. All data (products, categories, pagination) will be fetched via AJAX.
        return view('customer.products.index');
    }

    /**
     * Display the specified product to customers.
     */
    public function show(Product $product): View
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load('category');

        $review = null;
        if (Auth::check()) {
            $review = $product->reviews()->where('user_id', Auth::id())->first();
            // can review if bought (completed order) and not yet reviewed
            $hasBought = OrderItem::query()
                ->whereHas('order', function ($q) use ($product) {
                    $q->where('user_id', Auth::id())
                      ->where('status', 'completed');
                })
                ->where('product_id', $product->id)
                ->exists();
            $canReview = $hasBought && !$review;
        }

        $canReview = $canReview ?? false;

        return view('customer.products.show', compact('product', 'review', 'canReview'));
    }
}

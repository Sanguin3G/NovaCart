<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(): View
    {
        $categories = Category::active()->pluck('name', 'id');
        return view('admin.products.index', compact('categories'));
    }

    public function getData(Request $request): View
    {
        $query = Product::with('category')->latest();
        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $query->where('is_active', $request->status_filter === 'active');
        }
        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }
        if ($search = $request->input('search')) {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%"));
        }
        $products = $query->paginate(10)->withQueryString();
        return view('admin.products.partials.table', compact('products'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $product = Product::create($request->validated());

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product created successfully.',
                    'data' => $product
                ]);
            }

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product created successfully.');

        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create product.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to create product.');
        }
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View|JsonResponse
    {
        $categories = Category::active()->pluck('name', 'id');

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.products.form', [
                    'categories' => $categories,
                    'product' => new Product(),
                    'action' => route('admin.products.store'),
                    'submitButtonText' => __('Create Product'),
                ])->render()
            ]);
        }

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View|JsonResponse
    {
        $categories = Category::active()->pluck('name', 'id');

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.products.form', [
                    'product' => $product,
                    'categories' => $categories,
                    'isEdit' => true,
                    'action' => route('admin.products.update', $product),
                    'submitButtonText' => __('Update Product'),
                ])->render()
            ]);
        }

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            $product->delete();
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.'
            ]);
        } catch (Exception) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product.'
            ], 500);
        }
    }

    /**
     * Toggle product status.
     */
    public function toggleStatus(Product $product): JsonResponse
    {
        $product->update(['is_active' => !$product->is_active]);
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'is_active' => $product->is_active
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse|RedirectResponse
    {
        try {
            $product->update($request->validated());

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully.',
                    'data' => $product->fresh()
                ]);
            }

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product updated successfully.');

        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update product.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to update product.');
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(): View
    {
        $parentCategories = Category::active()
            ->whereNull('parent_id')
            ->pluck('name', 'id');
        return view('admin.categories.index', compact('parentCategories'));
    }

    public function getData(Request $request): View
    {
        $query = Category::with('parent')->withCount('products')->latest();
        if ($request->filled('parent_filter') && $request->parent_filter !== 'all') {
            $request->parent_filter === 'none'
                ? $query->whereNull('parent_id')
                : $query->where('parent_id', $request->parent_filter);
        }
        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $query->where('is_active', $request->status_filter === 'active');
        }
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }
        $categories = $query->paginate(10)->withQueryString();
        return view('admin.categories.partials.table', compact('categories'));
    }


    /**
     * Store a newly created category in storage.
     */
    public function store(StoreCategoryRequest $request): JsonResponse|RedirectResponse
    {
        try {
            $category = Category::create($request->validated());

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category created successfully.',
                    'data' => $category
                ]);
            }

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category created successfully.');

        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create category.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to create category.');
        }
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View|JsonResponse
    {
        $parentCategories = Category::active()
            ->whereNull('parent_id')
            ->pluck('name', 'id');

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.categories.form', [
                    'category' => new Category(),
                    'parentCategories' => $parentCategories,
                    'action' => route('admin.categories.store'),
                    'submitButtonText' => __('Create Category'),
                ])->render()
            ]);
        }

        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View|JsonResponse
    {
        $parentCategories = Category::active()
            ->where('id', '!=', $category->id)
            ->whereNull('parent_id')
            ->pluck('name', 'id');

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.categories.form', [
                    'category' => $category,
                    'parentCategories' => $parentCategories,
                    'isEdit' => true,
                    'action' => route('admin.categories.update', $category),
                    'submitButtonText' => __('Update Category'),
                ])->render()
            ]);
        }

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        if (!$category->isDeletable()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with subcategories. Please remove or reassign them first.'
            ], 422);
        }


        try {
            $category->delete();
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.'
            ]);
        } catch (Exception) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category.'
            ], 500);
        }
    }

    /**
     * Toggle category status.
     */
    public function toggleStatus(Category $category): JsonResponse
    {
        // Only check when deactivating a parent category with active children
        if ($category->parent_id === null && $category->is_active) {
            $hasActiveChildren = $category->children()->where('is_active', true)->exists();
            if ($hasActiveChildren) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot deactivate category with active subcategories.'
                ], 422);
            }
        }

        $category->update(['is_active' => !$category->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'is_active' => $category->is_active
        ]);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse|RedirectResponse
    {
        try {
            $category->update($request->validated());

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category updated successfully.',
                    'data' => $category->fresh()
                ]);
            }

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category updated successfully.');

        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update category.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to update category.');
        }
    }
}

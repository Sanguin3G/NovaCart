<?php

namespace App\Models;

use App\Libraries\Common;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['formatted_created_at', 'formatted_updated_at'];

    /**
     * ACCESSORS FOR COMPATIBILITY
     * These map old property names to the new database columns.
     */
    public function getImageAttribute(): ?string
    {
        return $this->image_url;
    }

    public function getStockQuantityAttribute(): int
    {
        return $this->stock;
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->is_active;
    }

    /**
     * Get formatted created_at date (d-m-Y)
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at ? Common::formatDate($this->created_at) : '';
    }

    /**
     * Get formatted updated_at date (d-m-Y)
     */
    public function getFormattedUpdatedAtAttribute(): string
    {
        return $this->updated_at ? Common::formatDate($this->updated_at) : '';
    }

    /**
     * The category that this product belongs to.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withDefault([
            'name' => 'Uncategorized',
            'id' => null
        ]);
    }

    /**
     * Scope a query to only include products in a specific category.
     *
     * @param Builder $query
     * @param int $categoryId
     * @return Builder
     */
    public function scopeInCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Get the category name with fallback.
     *
     * @return string
     */
    public function getCategoryNameAttribute(): string
    {
        return $this->category->name;
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to only include products in stock.
     */
    public function scopeInStock(Builder $query): void
    {
        $query->where('stock', '>', 0);
    }

    /**
     * Get product data for DataTables
     * @throws Exception
     */
    public function getProductData(Request $request): JsonResponse
    {
        $query = $this->with('category')
            ->select('products.*');

        // Status filter (cheapest check first - simple boolean comparison)
        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $status = $request->status_filter === 'active';
            $query->where('is_active', $status);
        }

        // Category filter (medium cost - single column integer comparison)
        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        // Search (most expensive - multiple LIKE queries with wildcards)
        if ($searchValue = $request->input('search_value')) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%$searchValue%")
                    ->orWhere('description', 'like', "%$searchValue%");
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('price', function ($product) {
                return $product->getFormattedPriceAttribute();
            })
            ->editColumn('is_active', function ($product) {
                return view('components.status-toggle', [
                    'url' => route('admin.products.toggleStatus', $product->id),
                    'checked' => $product->is_active
                ])->render();
            })
            ->addColumn('category_name', function ($product) {
                return $product->category->name ?? 'N/A';
            })
            ->addColumn('stock_status', function ($product) {
                return $product->isInStock() ? 'In Stock' : 'Out of Stock';
            })
            ->addColumn('actions', function ($product) {
                return view('partials.action_buttons', [
                    'editUrl' => route('admin.products.edit', $product->id),
                    'deleteUrl' => route('admin.products.destroy', $product->id),
                    'deleteClass' => 'delete-product'
                ]);
            })
            ->rawColumns(['is_active', 'actions'])
            ->make();
    }

    /**
     * Get formatted price with currency
     */
    public function getFormattedPriceAttribute(): string
    {
        return '€' . number_format($this->price, 2);
    }

    /**
     * Check if product is in stock
     */
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Decrement stock by given quantity
     */
    public function decrementStock(int $quantity = 1): bool
    {
        if ($this->stock < $quantity) {
            return false;
        }

        $this->decrement('stock', $quantity);
        return true;
    }

    /**
     * Toggle product status
     */
    public function toggleStatus(): void
    {
        $this->update(['is_active' => !$this->is_active]);
    }

    /**
     * Customer reviews (active only)
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function averageRating(): float
    {
        return (float) $this->reviews()->avg('rating');
    }
}

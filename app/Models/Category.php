<?php

namespace App\Models;

use App\Libraries\Common;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['formatted_created_at', 'formatted_updated_at'];

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
     * Get the parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get all products in this category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to only include main (top-level) categories.
     */
    public function scopeMainCategories(Builder $query): void
    {
        $query->whereNull('parent_id');
    }

    /**
     * Get full category hierarchy name
     */
    public function getHierarchyNameAttribute(): string
    {
        $names = [];
        $category = $this;

        while ($category) {
            array_unshift($names, $category->name);
            $category = $category->parent;
        }

        return implode(' > ', $names);
    }

    /**
     * Check if category can be deleted
     */
    public function isDeletable(): bool
    {
        // Only prevent deleting if there are subcategories
        return $this->children()->count() === 0;
    }

    /**
     * Get the child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(): void
    {
        $this->update(['is_active' => !$this->is_active]);
    }

    /**
     * Get all descendant category IDs (recursive)
     */
    public function getAllChildrenIds(): array
    {
        $ids = [];
        $this->loadMissing('children');

        foreach ($this->children as $child) {
            $ids[] = $child->id;
            array_push($ids, ...$child->getAllChildrenIds());
        }

        return $ids;
    }
}

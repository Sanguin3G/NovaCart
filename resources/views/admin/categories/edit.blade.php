@php use App\Models\Category; @endphp
{{-- resources/views/admin/categories/edit.blade.php --}}
<x-layouts.app :title="__('Edit Category')">
    @php
        $category ??= new Category(); // Initialize empty category if not set
            $action = route('admin.categories.update', $category->id);
            $submitButtonText = __('Update Category');
    @endphp

    <div class="nc-page"><header class="nc-page-header"><div><p class="nc-eyebrow">{{ __('Admin management') }}</p><h1 class="nc-title">{{ __('Edit category') }}</h1><p class="nc-subtitle">{{ __('Keep this catalogue group accurate and easy to understand.') }}</p></div><a href="{{ route('admin.categories.index') }}" class="nc-btn-secondary"><x-icon name="chevron-left" width="16" height="16" />{{ __('Back to categories') }}</a></header><section class="nc-card"><div class="nc-card-body max-w-3xl">@include('admin.categories.form', ['action' => $action, 'submitButtonText' => $submitButtonText, 'category' => $category, 'parentCategories' => $parentCategories])</div></section></div>
</x-layouts.app>

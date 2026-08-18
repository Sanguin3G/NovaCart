{{-- resources/views/admin/categories/create.blade.php --}}
<x-layouts.app :title="__('Create Category')">
    @php
        $action = route('admin.categories.store');
        $submitButtonText = __('Create Category');
        $category = new \App\Models\Category;
    @endphp

    <div class="nc-page"><header class="nc-page-header"><div><p class="nc-eyebrow">{{ __('Admin management') }}</p><h1 class="nc-title">{{ __('Create category') }}</h1><p class="nc-subtitle">{{ __('Add a clear group for products in your catalogue.') }}</p></div><a href="{{ route('admin.categories.index') }}" class="nc-btn-secondary"><x-icon name="chevron-left" width="16" height="16" />{{ __('Back to categories') }}</a></header><section class="nc-card"><div class="nc-card-body max-w-3xl">@include('admin.categories.form', ['action' => $action, 'submitButtonText' => $submitButtonText, 'category' => $category, 'parentCategories' => $parentCategories])</div></section></div>
</x-layouts.app>

{{-- resources/views/admin/products/create.blade.php --}}
<x-layouts.app :title="__('Create Product')">
    @php
        $action = route('admin.products.store');
        $submitButtonText = __('Create Product');
        $product = new \App\Models\Product;
    @endphp

    <div class="nc-page"><header class="nc-page-header"><div><p class="nc-eyebrow">{{ __('Admin management') }}</p><h1 class="nc-title">{{ __('Create product') }}</h1><p class="nc-subtitle">{{ __('Add a product with useful catalogue details and stock information.') }}</p></div><a href="{{ route('admin.products.index') }}" class="nc-btn-secondary"><x-icon name="chevron-left" width="16" height="16" />{{ __('Back to products') }}</a></header><section class="nc-card"><div class="nc-card-body max-w-3xl">@include('admin.products.form', ['action' => $action, 'submitButtonText' => $submitButtonText, 'product' => $product, 'categories' => $categories])</div></section></div>
</x-layouts.app>

@php use App\Models\Product; @endphp
{{-- resources/views/admin/products/edit.blade.php --}}
<x-layouts.app :title="__('Edit Product')">
    @php
        $product ??= new Product(); // Initialize empty product if not set
        $action = route('admin.products.update', $product->id);
        $submitButtonText = __('Update Product');
    @endphp

    <div class="nc-page"><header class="nc-page-header"><div><p class="nc-eyebrow">{{ __('Admin management') }}</p><h1 class="nc-title">{{ __('Edit product') }}</h1><p class="nc-subtitle">{{ __('Keep the catalogue details, stock, and availability accurate.') }}</p></div><a href="{{ route('admin.products.index') }}" class="nc-btn-secondary"><x-icon name="chevron-left" width="16" height="16" />{{ __('Back to products') }}</a></header><section class="nc-card"><div class="nc-card-body max-w-3xl">@include('admin.products.form', ['action' => $action, 'submitButtonText' => $submitButtonText, 'product' => $product, 'categories' => $categories ?? []])</div></section></div>
</x-layouts.app>

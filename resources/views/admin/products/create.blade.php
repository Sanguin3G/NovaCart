{{-- resources/views/admin/products/create.blade.php --}}
<x-layouts.app :title="__('Create Product')">
    @php
        $action = route('admin.products.store');
        $submitButtonText = __('Create Product');
        $product = new \App\Models\Product;
    @endphp

    @include('admin.products.form', [
        'action' => $action,
        'submitButtonText' => $submitButtonText,
        'product' => $product,
        'categories' => $categories,
    ])
</x-layouts.app> 
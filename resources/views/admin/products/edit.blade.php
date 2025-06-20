@php use App\Models\Product; @endphp
{{-- resources/views/admin/products/edit.blade.php --}}
<x-layouts.app :title="__('Edit Product')">
    @php
        $product ??= new Product(); // Initialize empty product if not set
        $action = route('admin.products.update', $product->id);
        $submitButtonText = __('Update Product');
    @endphp

    @include('admin.products.form', [
        'action' => $action,
        'submitButtonText' => $submitButtonText,
        'product' => $product,
        'categories' => $categories ?? [], // Initialize empty categories if not set
    ])
</x-layouts.app>

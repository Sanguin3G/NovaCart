@php use App\Models\Category; @endphp
{{-- resources/views/admin/categories/edit.blade.php --}}
<x-layouts.app :title="__('Edit Category')">
    @php
        $category ??= new Category(); // Initialize empty category if not set
            $action = route('admin.categories.update', $category->id);
            $submitButtonText = __('Update Category');
    @endphp

    @include('admin.categories.form', [
        'action' => $action,
        'submitButtonText' => $submitButtonText,
        'category' => $category,
        'parentCategories' => $parentCategories,
    ])
</x-layouts.app>

{{-- resources/views/admin/categories/create.blade.php --}}
<x-layouts.app :title="__('Create Category')">
    @php
        $action = route('admin.categories.store');
        $submitButtonText = __('Create Category');
        $category = new \App\Models\Category;
    @endphp

    @include('admin.categories.form', [
        'action' => $action,
        'submitButtonText' => $submitButtonText,
        'category' => $category,
        'parentCategories' => $parentCategories,
    ])
</x-layouts.app> 
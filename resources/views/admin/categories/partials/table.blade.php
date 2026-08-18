<div class="nc-table-wrap">
    <table class="nc-table">
        <thead><tr><th>#</th><th>{{ __('Name') }}</th><th>{{ __('Parent') }}</th><th>{{ __('Products') }}</th><th>{{ __('Status') }}</th><th>{{ __('Updated') }}</th><th>{{ __('Actions') }}</th></tr></thead>
        <tbody>
        @forelse($categories as $category)
            <tr><td>{{ $categories->firstItem() + $loop->index }}</td><td class="font-semibold text-gray-900 dark:text-white">{{ $category->name }}</td><td>{{ $category->parent?->name ?? '—' }}</td><td>{{ $category->products_count }}</td><td>@include('components.status-toggle', ['url' => route('admin.categories.toggleStatus', $category), 'checked' => $category->is_active])</td><td class="whitespace-nowrap">{{ $category->updated_at?->format('M d, Y') }}</td><td>@include('partials.action_buttons', ['editUrl' => route('admin.categories.edit', $category), 'deleteUrl' => route('admin.categories.destroy', $category), 'deleteClass' => 'delete-category', 'deleteData' => $category->isDeletable() ? null : 'data-prevent-delete="true"'])</td></tr>
        @empty
            <tr><td colspan="7" class="nc-table-empty">{{ __('No categories found.') }}</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@include('components.pagination.htmx', ['paginator' => $categories, 'target' => '#categories-table'])

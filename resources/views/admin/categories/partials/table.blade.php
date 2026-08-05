<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
        <thead class="bg-zinc-50 dark:bg-zinc-800"><tr><th class="px-4 py-3 text-left text-xs uppercase">#</th><th class="px-4 py-3 text-left text-xs uppercase">{{ __('Name') }}</th><th class="px-4 py-3 text-left text-xs uppercase">{{ __('Parent') }}</th><th class="px-4 py-3 text-left text-xs uppercase">{{ __('Products') }}</th><th class="px-4 py-3 text-left text-xs uppercase">{{ __('Status') }}</th><th class="px-4 py-3 text-left text-xs uppercase">{{ __('Updated') }}</th><th class="px-4 py-3 text-left text-xs uppercase">{{ __('Actions') }}</th></tr></thead>
        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
        @forelse($categories as $category)
            <tr class="hover:bg-orange-50/50 dark:hover:bg-zinc-800"><td class="px-4 py-3">{{ $categories->firstItem() + $loop->index }}</td><td class="px-4 py-3 font-medium">{{ $category->name }}</td><td class="px-4 py-3">{{ $category->parent?->name ?? '—' }}</td><td class="px-4 py-3">{{ $category->products_count }}</td><td class="px-4 py-3">@include('components.status-toggle', ['url' => route('admin.categories.toggleStatus', $category), 'checked' => $category->is_active])</td><td class="px-4 py-3">{{ $category->updated_at?->format('M d, Y') }}</td><td class="px-4 py-3">@include('partials.action_buttons', ['editUrl' => route('admin.categories.edit', $category), 'deleteUrl' => route('admin.categories.destroy', $category), 'deleteClass' => 'delete-category', 'deleteData' => $category->isDeletable() ? null : 'data-prevent-delete="true"'])</td></tr>
        @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-zinc-500">{{ __('No categories found.') }}</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@include('components.pagination.htmx', ['paginator' => $categories, 'target' => '#categories-table'])

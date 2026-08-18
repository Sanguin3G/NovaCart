@props(['paginator', 'target'])

@if ($paginator->hasPages())
    <nav class="mt-4 flex flex-col gap-3 border-t border-gray-200 pt-4 text-sm sm:flex-row sm:items-center sm:justify-between dark:border-gray-700" aria-label="Pagination navigation">
        <span class="text-gray-500 dark:text-gray-400">{{ __('Showing :from–:to of :total', ['from' => $paginator->firstItem(), 'to' => $paginator->lastItem(), 'total' => $paginator->total()]) }}</span>
        <div class="flex gap-2">
            @if ($paginator->onFirstPage())
                <span class="nc-btn-secondary pointer-events-none opacity-50">{{ __('Previous') }}</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" hx-get="{{ $paginator->previousPageUrl() }}" hx-target="{{ $target }}" hx-swap="innerHTML" hx-push-url="true" class="nc-btn-secondary">{{ __('Previous') }}</a>
            @endif
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" hx-get="{{ $paginator->nextPageUrl() }}" hx-target="{{ $target }}" hx-swap="innerHTML" hx-push-url="true" class="nc-btn-secondary">{{ __('Next') }}</a>
            @else
                <span class="nc-btn-secondary pointer-events-none opacity-50">{{ __('Next') }}</span>
            @endif
        </div>
    </nav>
@endif

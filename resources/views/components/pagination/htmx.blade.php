@props(['paginator', 'target'])

@if ($paginator->hasPages())
    <nav class="mt-4 flex items-center justify-between text-sm" aria-label="{{ __('Pagination') }}">
        <span class="text-zinc-500 dark:text-zinc-400">{{ __('Showing :from–:to of :total', ['from' => $paginator->firstItem(), 'to' => $paginator->lastItem(), 'total' => $paginator->total()]) }}</span>
        <div class="flex gap-2">
            @if ($paginator->onFirstPage())
                <span class="rounded border border-zinc-200 px-3 py-1 text-zinc-400 dark:border-zinc-700">{{ __('Previous') }}</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" hx-get="{{ $paginator->previousPageUrl() }}" hx-target="{{ $target }}" hx-swap="innerHTML" hx-push-url="true" class="rounded border border-zinc-200 px-3 py-1 dark:border-zinc-700">{{ __('Previous') }}</a>
            @endif
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" hx-get="{{ $paginator->nextPageUrl() }}" hx-target="{{ $target }}" hx-swap="innerHTML" hx-push-url="true" class="rounded border border-zinc-200 px-3 py-1 dark:border-zinc-700">{{ __('Next') }}</a>
            @else
                <span class="rounded border border-zinc-200 px-3 py-1 text-zinc-400 dark:border-zinc-700">{{ __('Next') }}</span>
            @endif
        </div>
    </nav>
@endif

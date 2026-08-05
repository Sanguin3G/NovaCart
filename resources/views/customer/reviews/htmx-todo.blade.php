<x-layouts.app :title="__('Products To Review')">
    <div class="mx-auto max-w-6xl py-8">
        <h1 class="mb-6 text-2xl font-semibold">{{ __('Products you have purchased but not reviewed yet') }}</h1>
        <div id="todo-reviews-table" hx-get="{{ route('reviews.todo') }}" hx-trigger="load" hx-swap="innerHTML"><div class="py-8 text-center text-zinc-500">{{ __('Loading…') }}</div></div>
    </div>
</x-layouts.app>

<x-button
    type="button"
    data-sidebar-toggle
    aria-label="{{ __('Toggle sidebar') }}"
    {{ $attributes->class(['shrink-0']) }}
>{{ $slot }}</x-button>

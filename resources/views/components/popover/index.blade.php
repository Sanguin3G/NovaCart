@props([
    'justify' => 'right',
    'align' => 'end',
])

@php
$x = match ($justify) {
    'left' => 'left-0 origin-left rtl:origin-right',
    'right' => 'right-0 origin-right rtl:origin-left',
};
$y = match ($align) {
    'top' => 'mt-1.5 top-full',
    'bottom' => 'mb-1.5 bottom-full',
};
@endphp

<div class="js-popover relative">
    <div class="js-popover-trigger inline-block">
        {{ $slot }}
    </div>
    <div {{ $menu->attributes->class([
        'js-popover-menu',
        'invisible opacity-0 scale-95',
        'transform transition ease-out duration-150',
        $x, $y,
        'absolute z-50',
        '[:where(&)]:min-w-48 p-[.3125rem]',
        'rounded-lg shadow-xs',
        'border border-gray-200 dark:border-gray-600',
        'bg-white dark:bg-gray-700',
        'focus:outline-hidden',
    ]) }}>
        {{ $menu }}
    </div>
</div>

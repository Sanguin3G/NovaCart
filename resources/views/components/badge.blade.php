@props([
    'color' => 'orange', // base Tailwind color name
    'variant' => 'light', // light | solid
])

@php
    // Map variant to bg/text classes
    $bg = $variant === 'solid'
        ? "bg-{$color}-600 text-white dark:bg-{$color}-500"
        : "bg-{$color}-100 text-{$color}-800 dark:bg-{$color}-900 dark:text-{$color}-100";
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {$bg}"]) }}>
    {{ $slot }}
</span> 
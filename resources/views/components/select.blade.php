@props([
    'size' => 'base',
])

@php
    $classes = [
        'block w-full appearance-none',
        'transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)] focus:ring-opacity-50',
        'pl-3 pr-8', // leave room for arrow icon
        'bg-white dark:bg-white/10 dark:disabled:bg-white/[7%]',
        'text-gray-700 disabled:text-gray-500 placeholder-gray-400 disabled:placeholder-gray-400/70 dark:text-gray-300 dark:disabled:text-gray-400 dark:placeholder-gray-400 dark:disabled:placeholder-gray-500',
        'rounded-lg border border-gray-200 border-b-gray-300/80 disabled:border-b-gray-200 dark:border-white/10 dark:disabled:border-white/5',
        'shadow-xs disabled:shadow-none dark:shadow-none',
        'aria-invalid:border-red-500',
        match ($size) {
            'base' => 'text-base sm:text-sm py-2 h-10 leading-[1.375rem]',
            'sm' => 'text-sm py-1.5 h-8 leading-[1.125rem]',
            'xs' => 'text-xs py-1.5 h-6 leading-[1.125rem]',
        },
        'bg-[url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' fill=\'none\' stroke=%22%23A0AEC0%22 stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\'%3E%3Cpolyline points=\'4 6 8 10 12 6\'/%3E%3C/svg%3E")] bg-no-repeat bg-[right_0.75rem_center] pointer-events-auto'
    ];
@endphp

<select {{ $attributes->class($classes) }}>
    {{ $slot }}
</select> 
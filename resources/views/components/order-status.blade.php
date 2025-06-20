{{-- Order status badge component --}}
@props(['status'])
@php
    $classes = [
        'completed' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400',
        'pending'    => 'bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-400',
        'processing' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400',
        'cancelled'  => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400',
        'shipped'    => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-400',
    ][$status] ?? 'bg-zinc-100 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-400';
@endphp

<span {{ $attributes->merge(['class' => "px-2 inline-flex text-xs leading-5 font-semibold rounded-full $classes"]) }}>
    {{ ucfirst($status) }}
</span> 
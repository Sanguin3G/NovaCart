@props(['name' => 'circle', 'size' => null])
@php
    $icon = strtolower((string) $name);
    $classes = $attributes->get('class', $size ? 'size-'.$size : 'size-5');
@endphp
<svg {{ $attributes->except('class')->merge(['class' => $classes, 'aria-hidden' => $attributes->get('aria-hidden', 'true')]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
    @if (str_contains($icon, 'user'))
        <circle cx="12" cy="8" r="3.5"/><path d="M4.5 20c.8-3.2 3.3-5 7.5-5s6.7 1.8 7.5 5"/>
    @elseif (str_contains($icon, 'shopping') || str_contains($icon, 'bag') || str_contains($icon, 'cart'))
        <path d="M5 8h14l-1 11H6L5 8Z"/><path d="M9 8a3 3 0 0 1 6 0"/>
    @elseif (str_contains($icon, 'package'))
        <path d="m4 7 8-4 8 4-8 4-8-4Z"/><path d="M4 7v10l8 4 8-4V7M12 11v10"/>
    @elseif (str_contains($icon, 'check') || str_contains($icon, 'shield'))
        <path d="m5 12 4 4L19 6"/><path d="M12 3 4 6v5c0 5 3.4 8.4 8 10 4.6-1.6 8-5 8-10V6l-8-3Z"/>
    @elseif (str_contains($icon, 'star'))
        <path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3Z"/>
    @elseif (str_contains($icon, 'tag'))
        <path d="M4 5v6l9 9 7-7-9-9H4Z"/><circle cx="8" cy="8" r="1"/>
    @elseif (str_contains($icon, 'receipt') || str_contains($icon, 'document'))
        <path d="M6 3h12v18l-3-2-3 2-3-2-3 2V3Z"/><path d="M9 8h6M9 12h6"/>
    @elseif (str_contains($icon, 'search') || str_contains($icon, 'magnifying'))
        <circle cx="10.5" cy="10.5" r="6"/><path d="m15 15 5 5"/>
    @elseif (in_array($icon, ['x', 'phosphor-x'], true) || str_contains($icon, 'close'))
        <path d="m6 6 12 12M18 6 6 18"/>
    @elseif (str_contains($icon, 'caret') || str_contains($icon, 'chevron'))
        <path d="{{ str_contains($icon, 'right') ? 'm9 5 7 7-7 7' : (str_contains($icon, 'up') ? 'm5 15 7-7 7 7' : 'm5 9 7 7 7-7') }}"/>
    @elseif (str_contains($icon, 'list'))
        <path d="M5 6h14M5 12h14M5 18h14"/>
    @elseif (str_contains($icon, 'lightning'))
        <path d="m13 2-9 12h7l-1 8 9-12h-7l1-8Z"/>
    @else
        <circle cx="12" cy="12" r="8"/>
    @endif
</svg>

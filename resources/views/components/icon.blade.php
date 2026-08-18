@props(['name' => 'circle', 'size' => null])
@php
    $icon = strtolower((string) $name);
    $classes = $attributes->get('class', $size ? 'size-'.$size : 'size-5');
    $is = fn (array $names): bool => collect($names)->contains(fn (string $value): bool => str_contains($icon, $value));
@endphp
<svg {{ $attributes->except('class')->merge(['class' => $classes, 'aria-hidden' => $attributes->get('aria-hidden', 'true')]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
    @if ($is(['house', 'home', 'dashboard', 'squares-four', 'squares-2x2']))
        <path d="m4 10 8-6 8 6v9a1 1 0 0 1-1 1h-4v-6H9v6H5a1 1 0 0 1-1-1v-9Z"/>
    @elseif ($is(['user']))
        <circle cx="12" cy="8" r="3.5"/><path d="M4.5 20c.8-3.2 3.3-5 7.5-5s6.7 1.8 7.5 5"/>
    @elseif ($is(['shopping', 'bag', 'cart']))
        <path d="M5 8h14l-1 11H6L5 8Z"/><path d="M9 8a3 3 0 0 1 6 0"/>
    @elseif ($is(['package', 'box']))
        <path d="m4 7 8-4 8 4-8 4-8-4Z"/><path d="M4 7v10l8 4 8-4V7M12 11v10"/>
    @elseif ($is(['check', 'shield']))
        <path d="m5 12 4 4L19 6"/><path d="M12 3 4 6v5c0 5 3.4 8.4 8 10 4.6-1.6 8-5 8-10V6l-8-3Z"/>
    @elseif ($is(['star']))
        <path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3Z"/>
    @elseif ($is(['tag']))
        <path d="M4 5v6l9 9 7-7-9-9H4Z"/><circle cx="8" cy="8" r="1"/>
    @elseif ($is(['receipt', 'document', 'file']))
        <path d="M6 3h12v18l-3-2-3 2-3-2-3 2V3Z"/><path d="M9 8h6M9 12h6"/>
    @elseif ($is(['search', 'magnifying']))
        <circle cx="10.5" cy="10.5" r="6"/><path d="m15 15 5 5"/>
    @elseif ($icon === 'x' || $is(['close']))
        <path d="m6 6 12 12M18 6 6 18"/>
    @elseif ($is(['caret', 'chevron']))
        <path d="{{ str_contains($icon, 'right') ? 'm9 5 7 7-7 7' : (str_contains($icon, 'up') ? 'm5 15 7-7 7 7' : 'm5 9 7 7 7-7') }}"/>
    @elseif ($is(['list']))
        <path d="M5 6h14M5 12h14M5 18h14"/>
    @elseif ($is(['lightning']))
        <path d="m13 2-9 12h7l-1 8 9-12h-7l1-8Z"/>
    @elseif ($is(['gear', 'settings', 'sliders']))
        <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-1.8 1.8-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.5v.1h-2.5v-.1a1.7 1.7 0 0 0-1-1.5 1.7 1.7 0 0 0-1.9.3l-.1.1-1.8-1.8.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.5-1H6.5v-2.5h.1a1.7 1.7 0 0 0 1.5-1 1.7 1.7 0 0 0-.3-1.9l-.1-.1 1.8-1.8.1.1a1.7 1.7 0 0 0 1.9.3 1.7 1.7 0 0 0 1-1.5V5h2.5v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.9-.3l.1-.1 1.8 1.8-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.5 1h.1v2.5h-.1a1.7 1.7 0 0 0-1.5 1Z"/>
    @elseif ($is(['sign-out', 'logout', 'log-out']))
        <path d="M10 5H5v14h5M14 8l4 4-4 4M18 12H9"/>
    @elseif ($is(['git', 'branch', 'repository', 'pull-request']))
        <circle cx="6" cy="6" r="2"/><circle cx="18" cy="18" r="2"/><path d="M6 8v5a5 5 0 0 0 5 5h5M12 6h4a2 2 0 0 1 2 2v8"/>
    @elseif ($is(['book', 'documentation', 'docs']))
        <path d="M5 4.5A2.5 2.5 0 0 1 7.5 2H19v17H7.5A2.5 2.5 0 0 0 5 21.5v-17Z"/><path d="M5 19.5A2.5 2.5 0 0 1 7.5 17H19M9 6h6M9 10h6"/>
    @elseif ($is(['moon']))
        <path d="M20 15.5A8.5 8.5 0 0 1 8.5 4 8.5 8.5 0 1 0 20 15.5Z"/>
    @elseif ($is(['sun']))
        <circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>
    @elseif ($is(['monitor', 'system']))
        <rect x="3" y="4" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4"/>
    @elseif ($is(['filter']))
        <path d="M4 5h16l-6 7v5l-4 2v-7L4 5Z"/>
    @elseif ($is(['plus', 'add']))
        <path d="M12 5v14M5 12h14"/>
    @elseif ($is(['pencil', 'edit']))
        <path d="m4 16-.7 4.7L8 20l10.5-10.5a2.1 2.1 0 0 0-3-3L5 17Z"/><path d="m14 8 3 3"/>
    @elseif ($is(['trash', 'delete']))
        <path d="M5 7h14M10 11v6M14 11v6M9 7V4h6v3m-9 0 1 14h10l1-14"/>
    @elseif ($is(['eye', 'view']))
        <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/>
    @elseif ($is(['ban', 'disable']))
        <circle cx="12" cy="12" r="8"/><path d="m7 7 10 10"/>
    @elseif ($is(['refresh', 'retry']))
        <path d="M20 11a8 8 0 0 0-14.5-4L4 9M4 5v4h4M4 13a8 8 0 0 0 14.5 4L20 15m0 4v-4h-4"/>
    @else
        <path d="M12 4v16M4 12h16"/>
    @endif
</svg>

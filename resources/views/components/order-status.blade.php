{{-- Order status badge component --}}
@props(['status'])
@php
    $classes = [
        'completed' => 'nc-badge-success',
        'pending' => 'nc-badge-warning',
        'processing' => 'nc-badge-neutral',
        'cancelled' => 'nc-badge-danger',
        'shipped' => 'nc-badge-neutral',
    ][$status] ?? 'nc-badge-neutral';
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ ucfirst($status) }}
</span>

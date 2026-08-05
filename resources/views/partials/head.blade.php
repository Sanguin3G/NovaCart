<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

@isset($title)
    <title>{{ $title }} | {{ config('app.name', 'NovaCart') }}</title>
@else
    <title>{{ config('app.name', 'NovaCart') }}</title>
@endisset

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="icon" type="image/png" href="{{ asset('images/Shopee.png') }}">
<script>
    (() => {
        const stored = localStorage.getItem('theme') || localStorage.getItem('appearance') || 'system';
        const dark = stored === 'dark' || (stored === 'system' && matchMedia('(prefers-color-scheme: dark)').matches);
        document.documentElement.classList.toggle('dark', dark);
        document.documentElement.dataset.theme = stored;
    })();
</script>

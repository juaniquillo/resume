@props([
    'title' => config('app.name'),
    'assets' => ['resources/css/app.css'],
    'nav' => null,
    'footer' => null,
    'theme' => null,
    'minimalView' => false,
    'description' => null,
    'image' => null,
    'noindex' => false,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth @if($minimalView) light @endif">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    @if($noindex)
        <!-- Indexing blocked by noindex prop -->
        <meta name="robots" content="noindex, nofollow">
    @endif

    <title>{{ $title }}</title>
    
    @if($description)
        <meta name="description" content="{{ $description }}">
        <meta property="og:description" content="{{ $description }}">
        <meta name="twitter:description" content="{{ $description }}">
    @endif

    <meta property="og:title" content="{{ $title }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    
    @if($image)
        <meta property="og:image" content="{{ $image }}">
        <meta name="twitter:image" content="{{ $image }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">

    {{-- Vite Assets --}}
    @vite($assets)

    @if(! $minimalView)
    <script>
        const htmlElement = document.documentElement;

        // Initialize theme immediately to prevent FOUC
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            htmlElement.classList.add('dark');
        } else {
            htmlElement.classList.remove('dark');
        }
    </script>
    @else 
    <style>
        html {
            -webkit-print-color-adjust: exact;
        }
        @page {
            margin: 12mm 5mm;
        }
        @page :first {
            margin-top: 10mm;
        }
    </style>
    @endif

    {{-- Fonts --}}
    @if ($theme)
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        @foreach ($theme->fontUrls() as $url)
            <link href="{{ $url }}" rel="stylesheet">
        @endforeach
    @else
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    @endif

    <style>
        body {
            font-family: {!! $theme ? $theme->fontFamily() : "'Space Mono', monospace" !!};
        }

    </style>
</head>
<body class="antialiased bg-white text-gray-900 dark:bg-gray-900 dark:text-white transition-colors duration-300">

    @if($nav)
        {{ $nav }}
    @endif

    <main>
        {{ $slot }}
    </main>

    @if($footer)
        {{ $footer }}
    @endif

</body>
</html>

@props([
    'title' => config('app.name'),
    'assets' => ['resources/css/app.css'],
    'nav' => null,
    'footer' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>

    {{-- Vite Assets --}}
    @vite($assets)

    {{-- Dark Mode Initializer --}}
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    {{-- Google Fonts: Space Mono --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Space Mono', monospace;
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

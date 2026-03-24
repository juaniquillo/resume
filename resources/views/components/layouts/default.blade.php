<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? config('page.title') ?? "Victor Sánchez's Resume" }}</title>

    @php
        $additionalAssets = $additionalAssets ?? [];
        $assets = array_merge(['resources/css/app.css', 'resources/js/app.js'], $additionalAssets);
    @endphp
    
    @vite($assets)
    
</head>
<body>

    <main>
        {{ $slot }}
    </main>
    
</body>
</html>
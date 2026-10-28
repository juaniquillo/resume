@props(['attrs' => null])
@php
    use App\Support\Helpers;

    // The backend-component package resolver passes `attributes` (plain
    // ComponentAttributeBag) and `slot`. Older versions passed the
    // DefaultAttributeBag as `attrs`; support both.
    $serverAttrs = $attrs ? $attrs->getAttributes() : [];

    $path = $serverAttrs['path'] ?? $attributes->get('path');
    $color = $serverAttrs['color'] ?? $attributes->get('color', 'currentColor');

    $maskStyle = '';
    if ($path) {
        $base64 = Helpers::svgToBase64($path);
        if ($base64 !== '') {
            $maskStyle = "background-color: {$color}; mask-image: url({$base64}); -webkit-mask-image: url({$base64}); mask-size: contain; mask-repeat: no-repeat; mask-position: center;";
        }
    }

    $existingStyle = $serverAttrs['style'] ?? $attributes->get('style');
    $combinedStyle = trim($maskStyle.($existingStyle ? ' '.$existingStyle : ''));
@endphp
<span {{ $attributes->except(['path', 'color', 'style']) }} @if($combinedStyle !== '') style="{{ $combinedStyle }}" @endif>{{ $slot ?? '' }}</span>

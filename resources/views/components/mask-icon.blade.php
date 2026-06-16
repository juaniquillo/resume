@props(['attrs' => null])
@php
    use App\Support\Helpers;

    $serverAttrs = [];
    $content = null;
    $slot = $slot ?? null;

    if ($attrs) {
        $serverAttrs = $attrs->getAttributes();
        $content = $attrs->content;

        $path = $serverAttrs['path'] ?? null;
        $color = $serverAttrs['color'] ?? 'currentColor';

        if ($path) {
            $base64 = Helpers::svgToBase64($path);
            $maskStyle = "background-color: $color; mask-image: url($base64); -webkit-mask-image: url($base64); mask-size: contain; mask-repeat: no-repeat; mask-position: center;";
            
            $serverAttrs['style'] = $maskStyle . ($serverAttrs['style'] ?? '');
            
            unset($serverAttrs['path']);
            unset($serverAttrs['color']);
        }
    }
@endphp
<span {{ $attributes->merge($serverAttrs) }}>{{ $content }}{{ $slot }}</span>

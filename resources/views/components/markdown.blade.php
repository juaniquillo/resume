@props([
    'attrs' => null,
])

<?php
    /** @var \Juaniquillo\BackendComponents\Components\DefaultAttributeBag $attrs */
    use Illuminate\Support\Str;
?>

@php
    $serverAttrs = [];
    $content = null;
    $slot = $slot ?? null;
    
    if($attrs) {

        $serverAttrs = $attrs->getAttributes();

        $contentRaw = $attrs->content->toArray()[0] ?? '';

        $content = Str::markdown($contentRaw);
        
    }

@endphp

<div {{ $attributes->merge($serverAttrs) }}>{!! $content !!}{{ $slot }}</div> 
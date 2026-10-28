@props([
    'attrs' => null,
])

<?php
    /** @var \Juaniquillo\BackendComponents\Components\DefaultAttributeBag|null $attrs */
    use Illuminate\Support\Str;

    ?>

@php
    // The backend-component package resolver passes `attributes` (plain
    // ComponentAttributeBag) and `slot` (DefaultContentsComponent). Older
    // versions passed the DefaultAttributeBag as `attrs`; support both.
    $raw = '';
    if ($attrs) {
        $raw = $attrs->content->toArray()[0] ?? '';
    } elseif (isset($slot) && method_exists($slot, 'toArray')) {
        $values = array_values($slot->toArray());
        $raw = $values[0] ?? '';
    }

    $content = is_string($raw) && $raw !== '' ? Str::markdown($raw) : null;
    $slot = $slot ?? null;
@endphp

<div {{ $attributes }}>{!! $content !!}</div>

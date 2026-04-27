@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Resume Builder" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md">
            <img src="{{ asset('images/brand.png') }}" alt="Juaniquillo" width="48" height="48">
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Resume Builder" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <img src="{{ asset('images/brand.png') }}" alt="Juaniquillo" width="48" height="48">
        </x-slot>
    </flux:brand>
@endif

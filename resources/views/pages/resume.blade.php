@props([
    'minimalView' => false,
    'showThemeToggle' => false,
])
<x-layouts.guest
    :title="($user->name ?? 'User') . ' - Resume'"
    :assets="['resources/css/resume.css', 'resources/js/resume.js']"
    :theme="$theme"
    :minimal-view="$minimalView"
    :description="$description ?? null"
    :image="$image ?? null"
    :noindex="$noindex ?? false"
>
    @if(! $minimalView)
        <x-slot:nav>
            <x-nav.resume class="mb-4" />
        </x-slot:nav>
    @endif

    <div class="container mx-auto">
        @if($showThemeToggle)
            @include('partials.theme-toggle-standalone')
        @endif
        
        {!! $resumeComponent !!}
        
    </div>

    @if(! $minimalView)
        <x-slot:footer>
            <x-footer class="mt-4" :user="$user" />
        </x-slot:footer>
    @endif
    
</x-layouts.guest>

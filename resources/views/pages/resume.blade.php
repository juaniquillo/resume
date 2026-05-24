@props([
    'minimalView' => false,
    'showThemeToggle' => false,
])
<x-layouts.guest
    :title="($user->name ?? 'User') . ' - Resume'"
    :assets="['resources/css/resume.css', 'resources/js/resume.js']"
    :theme="$theme"
    :is-pdf="$minimalView"
>
    @if(! $minimalView)
        <x-slot:nav>
            <x-nav.resume />
        </x-slot:nav>
    @endif

    <div class="container mx-auto">
        @if($showThemeToggle)
            @include('partials.theme-toggle-standalone')
        @endif
        
        {!! $resumeComponent !!}
        
        @if(! $minimalView)
            <x-slot:footer>
                <x-footer :user="$user" />
            </x-slot:footer>
        @endif
    </div>
</x-layouts.guest>

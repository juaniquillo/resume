<x-layouts.guest
    title="{{ $name }} - Resume Preview"
    :assets="['resources/css/resume.css', 'resources/js/resume.js']"
    :theme="$theme"
    :minimalView="false"
>
    <div class="container mx-auto">
        @include('partials.theme-toggle-standalone')
        
        {!! $resumeComponent !!}
    </div>
</x-layouts.guest> 
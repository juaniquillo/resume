<x-layouts.guest
    title="{{ $name }} - Resume Preview"
    :assets="['resources/css/resume.css', 'resources/js/resume.ts']"
    :theme="$theme"
    :minimalView="false"
    :noindex="true"
>
    <div class="container mx-auto">
        @include('partials.theme-toggle-standalone')
        
        {!! $resumeComponent !!}
    </div>
</x-layouts.guest> 
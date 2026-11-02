<x-layouts.guest
    :title="'Showcase - ' . $themeLabel"
    :assets="['resources/css/resume.css', 'resources/js/resume.ts']"
    :theme="$theme"
    :minimal-view="true"
    :theme-mode="$dark ? 'dark' : null"
    :noindex="true"
>
    <div class="container mx-auto">
        {!! $resumeComponent !!}
    </div>

</x-layouts.guest>

@props([
    'isPdf' => false,
])
<x-layouts.guest
    :title="($user->name ?? 'User') . ' - Resume'"
    :assets="['resources/css/resume.css', 'resources/js/resume.js']"
    :theme="$theme"
    :is-pdf="$isPdf"
>
    @if(! $isPdf)
        <x-slot:nav>
            <x-nav.resume />
        </x-slot:nav>
    @endif

    <div class="container mx-auto">
        <main>
            {!! $resumeComponent !!}
        </main>

        @if(! $isPdf)
            <x-slot:footer>
                <x-footer :user="$user" />
            </x-slot:footer>
        @endif
    </div>
</x-layouts.guest>

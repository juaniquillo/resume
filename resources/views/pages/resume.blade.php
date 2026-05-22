<x-layouts.guest
    :title="($user->name ?? 'User') . ' - Resume'"
    :assets="['resources/css/resume.css', 'resources/js/resume.js']"
    :theme="$theme"
>
    <x-slot:nav>
        <x-nav.resume />
    </x-slot:nav>

    <div class="container mx-auto">
        <main>
            {!! $resumeComponent !!}
        </main>

        <x-slot:footer>
            <x-footer :user="$user" />
        </x-slot:footer>
    </div>
</x-layouts.guest>

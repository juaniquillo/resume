<x-layouts::app.sidebar :title="$title ?? null">

    <flux:main>
        <x-alerts class="mb-4 text-sm" />

        {{ $slot }}
    </flux:main>

</x-layouts::app.sidebar>

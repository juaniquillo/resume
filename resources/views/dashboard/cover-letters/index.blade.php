<x-layouts::app :title="__('Dashboard')">

    <flux:heading size="xl" level="1">{{ __("Create/Update Cover Letter") }}</flux:heading>

    <div class="max-w-xl mt-6">
        <livewire:resume.cover-letters.cover-letter-update />
    </div>

</x-layouts::app>

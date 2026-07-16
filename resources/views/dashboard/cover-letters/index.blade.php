<x-layouts::app :title="__('Dashboard')">

    <flux:heading size="xl" level="1">{{ __("Cover Letters") }}</flux:heading>

    <div class="mt-6">
        <livewire:resume.cover-letters.create-cover-letter />
    </div>

    <div class="mt-6">
        <livewire:resume.cover-letters.cover-letters-table />
    </div>

</x-layouts::app>

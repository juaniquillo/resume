<x-layouts::app :title="__('Dashboard')">

    <flux:heading size="xl" level="1">{{ __("Works") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <livewire:resume.works.create-work />
    </div>

    <flux:separator variant="subtle" class="mt-6" />

    <x-table-container>
        <livewire:resume.works.works-table />
    </x-table-container>

</x-layouts::app>

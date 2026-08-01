<x-layouts::app :title="__('Interests')">

    <flux:heading size="xl" level="1">{{ __("Interests") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <livewire:resume.interests.create-interest />
    </div>

    <livewire:resume.interests.interests-table />

</x-layouts::app>

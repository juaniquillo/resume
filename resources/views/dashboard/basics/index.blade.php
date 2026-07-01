<x-layouts::app :title="__('Dashboard')">
    
    <flux:heading size="xl" level="1">{{ __("Basic Info") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <livewire:basics.update-basics />
    </div>
</x-layouts::app>

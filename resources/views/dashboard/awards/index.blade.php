<x-layouts::app :title="__('Awards')">
    
    <flux:heading size="xl" level="1">{{ __("Awards") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <livewire:resume.awards.create-award />
    </div>

    <livewire:resume.awards.awards-table />
    
</x-layouts::app>

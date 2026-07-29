<x-layouts::app :title="__('Publications')">
    
    <flux:heading size="xl" level="1">{{ __("Publications") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <livewire:resume.publications.create-publication />
    </div>

    <livewire:resume.publications.publications-table />
    
</x-layouts::app>

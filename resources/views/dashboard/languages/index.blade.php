<x-layouts::app :title="__('Languages')">
    
    <flux:heading size="xl" level="1">{{ __("Languages") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <livewire:resume.languages.create-language />
    </div>

    <livewire:resume.languages.languages-table />
    
</x-layouts::app>

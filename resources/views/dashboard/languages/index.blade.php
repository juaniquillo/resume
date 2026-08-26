<x-layouts::app :title="__('Languages')">
    
    <flux:heading size="xl" level="1">{{ __("Languages") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <flux:text class="mb-4">
            {{ __("You can keep up to :limit languages at a time.", ['limit' => $limit]) }}
        </flux:text>
        <livewire:resume.languages.create-language />
    </div>

    <livewire:resume.languages.languages-table />
    
</x-layouts::app>

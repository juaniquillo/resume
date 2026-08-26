<x-layouts::app :title="__('Publications')">
    
    <flux:heading size="xl" level="1">{{ __("Publications") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <flux:text class="mb-4">
            {{ __("You can keep up to :limit publications at a time.", ['limit' => $limit]) }}
        </flux:text>
        <livewire:resume.publications.create-publication />
    </div>

    <livewire:resume.publications.publications-table />
    
</x-layouts::app>

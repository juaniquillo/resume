<x-layouts::app :title="__('Awards')">
    
    <flux:heading size="xl" level="1">{{ __("Awards") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <flux:text class="mb-4">
            {{ __("You can keep up to :limit awards at a time.", ['limit' => $limit]) }}
        </flux:text>
        <livewire:resume.awards.create-award />
    </div>

    <livewire:resume.awards.awards-table />
    
</x-layouts::app>

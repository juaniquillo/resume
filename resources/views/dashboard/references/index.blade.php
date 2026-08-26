<x-layouts::app :title="__('References')">

    <flux:heading size="xl" level="1">{{ __("References") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <flux:text class="mb-4">
            {{ __("You can keep up to :limit references at a time.", ['limit' => $limit]) }}
        </flux:text>
        <livewire:resume.references.create-reference />
    </div>

    <livewire:resume.references.references-table />

</x-layouts::app>

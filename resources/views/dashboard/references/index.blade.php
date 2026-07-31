<x-layouts::app :title="__('References')">

    <flux:heading size="xl" level="1">{{ __("References") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <livewire:resume.references.create-reference />
    </div>

    <livewire:resume.references.references-table />

</x-layouts::app>

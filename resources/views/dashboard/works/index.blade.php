<x-layouts::app :title="__('Dashboard')">

    <flux:heading size="xl" level="1">{{ __("Works") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <flux:text class="mb-4">
            {{ __("You can keep up to :limit work experiences at a time.", ['limit' => $limit]) }}
        </flux:text>
        <livewire:resume.works.create-work />
    </div>

    <livewire:resume.works.works-table />
    

</x-layouts::app>

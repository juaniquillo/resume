<x-layouts::app :title="__('Interests')">

    <flux:heading size="xl" level="1">{{ __("Interests") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <flux:text class="mb-4">
            {{ __("You can keep up to :limit interests at a time.", ['limit' => $limit]) }}
        </flux:text>
        <livewire:resume.interests.create-interest />
    </div>

    <livewire:resume.interests.interests-table />

</x-layouts::app>

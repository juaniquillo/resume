<x-layouts::app :title="__('Certificates')">
    
    <flux:heading size="xl" level="1">{{ __("Certificates") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <flux:text class="mb-4">
            {{ __("You can keep up to :limit certificates at a time.", ['limit' => $limit]) }}
        </flux:text>
        <livewire:resume.certificates.create-certificate />
    </div>

    <livewire:resume.certificates.certificates-table />
    
</x-layouts::app>

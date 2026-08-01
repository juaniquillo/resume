<x-layouts::app :title="__('Certificates')">
    
    <flux:heading size="xl" level="1">{{ __("Certificates") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        <livewire:resume.certificates.create-certificate />
    </div>

    <livewire:resume.certificates.certificates-table />
    
</x-layouts::app>

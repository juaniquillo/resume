<x-layouts::app :title="__('Dashboard')">

    <flux:heading size="xl" level="1">{{ __("OG Image Management") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        
        <flux:text class="mb-4">
            {{ __("View and regenerate your Open Graph image for social sharing.") }}
        </flux:text>

        <livewire:resume.og-image-reset />
    </div>
    
</x-layouts::app>

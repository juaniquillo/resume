<x-layouts::app :title="__('Dashboard')">

    <flux:heading size="xl" level="1">{{ __("Works") }}</flux:heading>
    
    <div class="max-w-xl mt-6">
        {{-- {{ $form }} --}}
        <livewire:resume.works.create-work />
    </div>

    @if ($table ?? null)
        
        <flux:separator variant="subtle" class="mt-6" />
        
        <x-table-container :paginator="$paginator">
            {{ $table }}
        </x-table-container> 

    @endif
</x-layouts::app>

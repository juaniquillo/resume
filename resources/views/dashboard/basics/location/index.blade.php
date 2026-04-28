<x-layouts::app :title="__('Dashboard')">

    <flux:heading size="xl" level="1">{{  __("Create Your Location") }}</flux:heading>

    @if ($basics)
        
        <div class="max-w-xl mt-6">
            {{ $form }}
        </div>
        
       

    @else
        <p class="mt-6">{{ __('basics.errors.basics_not_found') }}</p>
    @endif
    
</x-layouts::app>

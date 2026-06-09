<div {{ $attributes }}>
    @if ($slug)
        <flux:button 
            wire:click="toggle"
            wire:loading.attr="disabled"
            class="cursor-pointer justify-start overflow-hidden w-full" 
            size="xs" 
            variant="primary" 
            color="{{ $isDraft ? 'zinc' : 'emerald'}}"
            icon="{{ $isDraft ? 'pencil-square' : 'globe-alt' }}" 
            title="{{ $isDraft ? __('Draft (click to change)') : __('Published (click to change)') }}"
        >
            <span class="truncate">{{ $isDraft ? __('Draft') : __('Published') }}</span>
        </flux:button>
    @endif
</div >

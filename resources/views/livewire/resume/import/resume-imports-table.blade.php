<div @if($this->hasActiveImports) wire:poll.3s @endif>
    @if ($table ?? null)

        <flux:separator variant="subtle" class="mt-6" />
        
        <x-table-container>
            {{ $table }}
        </x-table-container>

    @endif
</div>

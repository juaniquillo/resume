<div>
    @if ($table ?? null)

        <flux:separator variant="subtle" class="mt-6" />
        
        <div @if($this->hasActiveExports) wire:poll.5s @endif>
            <x-table-container>
                {{ $table }}
            </x-table-container>
        </div>

    @endif
</div>

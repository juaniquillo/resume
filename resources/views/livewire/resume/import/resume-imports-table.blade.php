<div>
    @if ($table ?? null)

        <flux:separator variant="subtle" class="mt-6" />
    
        <div @if($this->hasActiveImports) wire:poll.5s @endif></div>
            <x-table-container>
                {{ $table }}
            </x-table-container>
        </div>

    @endif
</div>

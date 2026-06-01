<div>
    <flux:heading size="xl" level="1">{{ __("Section Ordering") }}</flux:heading>
    
    <flux:text class="mt-2 mb-6">
        {{ __("Drag and drop sections to change their display order on your public resume. 'Basics' and 'Downloads' sections are fixed.") }}
    </flux:text>

    <div class="max-w-md">
        <ul wire:sort="handleSort" class="space-y-2">
            @foreach ($this->sections as $section)
                <li 
                    wire:key="section-{{ $section->value }}" 
                    wire:sort:item="{{ $section->value }}"
                    class="flex items-center gap-3 p-3 bg-gray-100 dark:bg-back-table border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-sm group"
                >
                    <div wire:sort:handle class="cursor-grab active:cursor-grabbing p-1 -m-1 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                        <flux:icon.bars-2 class="size-5" />
                    </div>

                    <flux:text class="font-medium flex-1">{{ $section->label }}</flux:text>
                </li>
            @endforeach
        </ul>
    </div>
</div>

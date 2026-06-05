<div>
    <div class="mb-6 p-4 border border-dashed border-gray-300 dark:border-white/10 rounded-lg bg-gray-50 dark:bg-white/5 flex flex-col items-center relative overflow-hidden">
        
        <div wire:loading wire:target="regenerate" class="absolute inset-0 bg-white/50 dark:bg-black/50 flex items-center justify-center z-10 backdrop-blur-[1px]">
            <flux:icon.loading class="absolute inset-0 m-auto" />
        </div>

        <small class="mb-2 text-gray-500 dark:text-gray-400 font-medium">
            {{ __('Current OG Image Preview:') }}
        </small>

        <img 
            src="{{ $this->src }}" 
            class="max-w-full h-auto rounded shadow-lg border border-gray-200 dark:border-white/10"
            alt="OG Image Preview"
            width="{{ $this->width }}"
            height="{{ $this->height }}"
        >
        
    </div>

    <div class="flex flex-col items-center gap-4">
        <flux:button 
            wire:click="regenerate" 
            wire:loading.attr="disabled"
            variant="primary" 
            color="blue"
            class="cursor-pointer"
        >
            <span>
                {{ __('Regenerate OG Image') }}
            </span>
        </flux:button>
        
    </div>
</div>

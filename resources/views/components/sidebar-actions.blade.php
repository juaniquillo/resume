<div {{ $attributes }}>
    <div class="flex items-center gap-2 mb-2 px-2">
        <flux:modal.trigger name="sidebar-preview" >
            <flux:button class="cursor-pointer shrink-0" title="{{ __('Preview') }}" size="sm" variant="primary" icon="eye"></flux:button>
        </flux:modal.trigger>

        @php
            use \App\Support\Helpers;
            
            $user = auth()->user();
            $options = $user->generalOptions;
            $isDraft = Helpers::isResumeInDraftState($user);
            $slug = $options?->slug;
        @endphp

        @if ($slug)
            <flux:button 
                class="cursor-pointer justify-start overflow-hidden" 
                size="sm" 
                variant="primary" 
                color="{{ $isDraft ? 'zinc' : 'emerald'}}"
                icon="{{ $isDraft ? 'pencil-square' : 'globe-alt' }}" 
                href="{{ route('resume', $slug) }}" 
                target="_blank"
                title="{{ $isDraft ? __('Draft (click to view)') : __('Published (click to view)') }}"
            >
                <span class="truncate">{{ $isDraft ? __('Draft') : __('Published') }}</span>
            </flux:button>
        @endif
    </div>

    <flux:modal name="sidebar-preview" variant="flyout" class="w-full max-w-7xl p-0!">
       <livewire:resume.preview-iframe />
    </flux:modal>
</div>
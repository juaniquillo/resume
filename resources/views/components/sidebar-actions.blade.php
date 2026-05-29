<div class="flex items-center gap-2 mb-2 px-2">
    <flux:modal.trigger name="sidebar-preview" >
        <flux:button class="cursor-pointer shrink-0" title="{{ __('Preview') }}" size="sm" variant="primary" icon="eye"></flux:button>
    </flux:modal.trigger>

    @php
        $options = auth()->user()->generalOptions;
        $isDraft = $options?->is_draft;
        $slug = $options?->slug;
    @endphp

    @if ($slug)
        <flux:button 
            class="cursor-pointer flex-1 justify-start overflow-hidden" 
            size="sm" 
            variant="ghost" 
            icon="{{ $isDraft ? 'pencil-square' : 'globe-alt' }}" 
            href="{{ route('resume', $slug) }}" 
            target="_blank"
            title="{{ $isDraft ? __('Draft (click to view)') : __('Published (click to view)') }}"
        >
            <span class="truncate">{{ $isDraft ? __('Draft') : __('Published') }}</span>
        </flux:button>
    @endif
</div>

<flux:modal name="sidebar-preview" variant="flyout" class="w-full h-full p-0!">
    <iframe src="{{ route('dashboard.resume.preview') }}" class="w-full h-full border-0"></iframe>
</flux:modal>
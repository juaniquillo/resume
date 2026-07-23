<div {{ $attributes }}>
    <div class="flex items-center gap-2 mb-2 px-2">
        <flux:modal.trigger name="sidebar-preview" >
            <flux:button class="cursor-pointer shrink-0" title="{{ __('Preview') }}" size="xs" variant="primary" color="sky" icon="eye"></flux:button>
        </flux:modal.trigger>

        @php
            $resumeUrl = route('resume', [Auth::user()->slug]);
        @endphp
        <flux:button class="cursor-pointer shrink-0" title="{{ __('Visit') }}" :href="$resumeUrl" target="_blank" variant="primary" color="zinc" size="xs" icon="link" />

        <livewire:options.toggle-draft-state />
    </div>

    <flux:modal name="sidebar-preview" variant="flyout" class="w-full max-w-7xl p-0!">
       <livewire:resume.preview-iframe />
    </flux:modal>
</div>
<flux:modal.trigger name="sidebar-preview" >
    <flux:button class="cursor-pointer" title="Preview" size="sm" variant="primary" icon="eye"></flux:button>
</flux:modal.trigger>

<flux:modal name="sidebar-preview" variant="flyout" class="w-full h-full p-0!">
    <iframe src="{{ route('dashboard.resume.preview') }}" class="w-full h-full border-0"></iframe>
</flux:modal>
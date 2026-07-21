<x-layouts.guest
    :title="__('Cover Letter')"
    :theme="$theme"
    :minimal-view="true"
>
    <div class="container mx-auto">
        <div class="prose max-w-none">
            {{ $coverLetter }}
        </div>
    </div>
</x-layouts.guest>

@props([
    'paginator' => null
])

<div class="px-5 py-2 bg-gray-100 dark:bg-back-table border border-gray-300 dark:border-slate-700 rounded-lg mt-6 shadow">
    
    {{ $slot }}

    @if ($paginator)
        <div class="py-2">
            {{ $paginator->links() }}
        </div>
    @endif
    
</div>
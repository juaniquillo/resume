<div>
    @if ($table)
        {{ $table }}
        {{ $paginator->links() }}
    @else
        <p>No cover letters yet.</p>
    @endif
</div>

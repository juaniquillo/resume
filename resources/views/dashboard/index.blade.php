@php
    use App\Components\Nav\ResumeNav;
    use App\Components\Nav\ResumeOptionNav;
    use App\Components\Nav\ToolsNav;

    $cards = ResumeNav::makeCards();
    $toolsCards = ToolsNav::makeCards();
    $optionsCards = ResumeOptionNav::makeCards();
@endphp

<x-layouts::app :title="__('Dashboard')">
    <div class="max-w-4xl">
        @if (! $hasBasics)
            @include('dashboard.landing.onboarding')
        @else
            @include('dashboard.landing.standard')
        @endif
    </div>
</x-layouts::app>

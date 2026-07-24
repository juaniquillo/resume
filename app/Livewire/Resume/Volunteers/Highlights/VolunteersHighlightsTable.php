<?php

namespace App\Livewire\Resume\Volunteers\Highlights;

use App\Cruds\Schema\Highlights\HighlightsCrud;
use App\Livewire\Concerns\IsLivewireTable;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Collection;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class VolunteersHighlightsTable extends Component
{
    use IsLivewireTable;

    public Volunteer $volunteer;

    public function mount(Volunteer $volunteer)
    {
        $this->volunteer = $volunteer;
    }

    #[On('resume-updated')]
    #[Computed]
    public function getModels(): Collection
    {
        return $this->volunteer->highlights()->latest()->get();
    }

    private function crud()
    {
        return HighlightsCrud::build(baseRoute: 'dashboard.volunteers.highlights');
    }

    private function table(): ?BackendComponent
    {
        $models = $this->getModels();
        if ($models->isEmpty()) {
            return null;
        }

        return $this->crud()->makeTable($models);
    }

    public function render()
    {
        return view('livewire.resume.volunteers.highlights.highlights-table')
            ->with(['table' => $this->table()]);
    }
}

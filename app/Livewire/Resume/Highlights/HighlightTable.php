<?php

namespace App\Livewire\Resume\Highlights;

use App\Cruds\Squema\Highlights\HighlightsCrud;
use App\Livewire\Concerns\IsLivewireTable;
use App\Models\Contracts\HighlightModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class HighlightTable extends Component
{
    use IsLivewireTable;

    #[Locked]
    public Model|HighlightModel|null $model = null;

    public function mount(Model|HighlightModel $model)
    {
        $this->model = $model;
    }

    /** @throws ModelNotFoundException */
    #[On('resume-updated')]
    #[Computed]
    public function getModels(): Collection
    {
        $highlights = $this->model
            ->highlights()
            ->latest()
            ->get();

        return $highlights;
    }

    private function crud()
    {
        return HighlightsCrud::build([], [], baseRoute: 'dashboard.works.highlights');
    }

    private function table(): ?BackendComponent
    {
        $models = $this->getModels();

        if ($models->isEmpty()) {
            return null;
        }

        return $this->crud()
            ->makeTable($models);
    }

    public function render()
    {
        return view('livewire.resume.highlights.highlight_table')
            ->with('table', $this->table());
    }
}

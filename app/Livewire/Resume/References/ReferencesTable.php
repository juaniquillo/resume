<?php

namespace App\Livewire\Resume\References;

use App\Cruds\Schema\References\ReferencesCrud;
use App\Cruds\Schema\References\Renderers\ReferencesLivewireTableRenderer;
use App\Livewire\Concerns\IsLivewireTable;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ReferencesTable extends Component
{
    use IsLivewireTable;

    /** @throws ModelNotFoundException */
    #[On('resume-updated')]
    #[Computed]
    public function getModels(): Collection
    {
        /** @var User $user */
        $user = Auth::user();

        $references = $user->references();

        return $references->get();
    }

    private function crud()
    {
        return ReferencesCrud::build(
            tableRenderer: ReferencesLivewireTableRenderer::make(),
        );
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
        return view('livewire.resume.references.references-table')
            ->with(['table' => $this->table()]);
    }
}

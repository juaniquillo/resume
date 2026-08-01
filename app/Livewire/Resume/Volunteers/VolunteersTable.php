<?php

namespace App\Livewire\Resume\Volunteers;

use App\Cruds\Schema\Volunteers\Renderers\VolunteersLivewireTableRenderer;
use App\Cruds\Schema\Volunteers\VolunteersCrud;
use App\Livewire\Concerns\IsLivewireTable;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class VolunteersTable extends Component
{
    use IsLivewireTable;

    #[On('resume-updated')]
    #[Computed]
    public function getModels(): Collection
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->volunteers()->latest()->get();
    }

    private function crud()
    {
        return VolunteersCrud::build(
            tableRenderer: VolunteersLivewireTableRenderer::make(),
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
        return view('livewire.resume.volunteers.volunteers-table')
            ->with(['table' => $this->table()]);
    }
}

<?php

namespace App\Livewire\Resume\Profiles;

use App\Cruds\Schema\Profiles\ProfilesCrud;
use App\Cruds\Schema\Profiles\Renderers\ProfilesLivewireTableRenderer;
use App\Livewire\Concerns\IsLivewireTable;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ProfilesTable extends Component
{
    use IsLivewireTable;

    /** @throws ModelNotFoundException */
    #[On('resume-updated')]
    #[Computed]
    public function getModels(): Collection
    {
        /** @var User $user */
        $user = Auth::user();
        $basics = $user->resumeBasics();

        if (! $basics) {
            return new Collection;
        }

        return $basics->profiles()->get();
    }

    private function crud()
    {
        return ProfilesCrud::build(
            tableRenderer: ProfilesLivewireTableRenderer::make(),
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
        return view('livewire.resume.profiles.profiles-table')
            ->with(['table' => $this->table()]);
    }
}

<?php

namespace App\Livewire\Resume\Education;

use App\Cruds\Schema\Education\EducationCrud;
use App\Cruds\Schema\Education\Renderers\EducationLivewireTableRenderer;
use App\Livewire\Concerns\IsLivewireTable;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class EducationTable extends Component
{
    use IsLivewireTable;

    #[On('resume-updated')]
    #[Computed]
    public function getModels(): Collection
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->education()->latest()->get();
    }

    private function crud()
    {
        return EducationCrud::build(
            tableRenderer: EducationLivewireTableRenderer::make(),
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
        return view('livewire.resume.education.education-table')
            ->with(['table' => $this->table()]);
    }
}

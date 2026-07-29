<?php

namespace App\Livewire\Resume\Skills;

use App\Cruds\Schema\Skills\Renderers\SkillsLivewireTableRenderer;
use App\Cruds\Schema\Skills\SkillsCrud;
use App\Livewire\Concerns\IsLivewireTable;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class SkillsTable extends Component
{
    use IsLivewireTable;

    /** @throws ModelNotFoundException */
    #[On('resume-updated')]
    #[Computed]
    public function getModels(): Collection
    {
        /** @var User $user */
        $user = Auth::user();

        $skill = $user->skills();

        return $skill->get();
    }

    private function crud()
    {
        return SkillsCrud::build(
            tableRenderer: SkillsLivewireTableRenderer::make(),
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
        return view('livewire.resume.skills.skills-table')
            ->with(['table' => $this->table()]);
    }
}

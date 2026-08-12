<?php

namespace App\Livewire\Resume\Import;

use App\Cruds\Schema\ResumeImport\Renderers\ResumeImportLivewireTableRenderer;
use App\Cruds\Schema\ResumeImport\ResumeImportCrud;
use App\Livewire\Concerns\IsLivewireTable;
use App\Models\ResumeImport;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ResumeImportsTable extends Component
{
    use IsLivewireTable;

    #[On('resume-updated')]
    #[Computed]
    public function getModels(): Collection
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->resumeImports()->latest()->get();
    }

    #[Computed]
    public function hasActiveImports(): bool
    {
        return $this->getModels()->contains(function (Model $import) {
            /** @var ResumeImport $import */
            return $import->status->processing();
        });
    }

    private function crud()
    {
        return ResumeImportCrud::build(
            tableRenderer: ResumeImportLivewireTableRenderer::make(),
        );
    }

    private function table(): ?BackendComponent
    {
        $models = $this->getModels();
        if ($models->isEmpty()) {
            return null;
        }

        return $this
            ->crud()
            ->makeTable($models);
    }

    public function render()
    {
        return view('livewire.resume.import.resume-imports-table')
            ->with(['table' => $this->table()]);
    }
}

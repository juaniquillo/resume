<?php

namespace App\Livewire\Resume\Export;

use App\Cruds\Schema\ResumeExport\Renderers\ResumeExportLivewireTableRenderer;
use App\Cruds\Schema\ResumeExport\ResumeExportCrud;
use App\Enums\ProcessStatus;
use App\Livewire\Concerns\IsLivewireTable;
use App\Models\ResumeExport;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ResumeExportsTable extends Component
{
    use IsLivewireTable;

    #[On('resume-updated')]
    #[Computed]
    public function getModels(): Collection
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->resumeExports()->latest()->get();
    }

    #[Computed]
    public function hasActiveExports(): bool
    {
        return $this->getModels()->contains(function (Model $export) {
            /** @var ResumeExport $export */
            return \in_array($export->status, [ProcessStatus::PENDING, ProcessStatus::PROCESSING]);
        });
    }

    private function crud()
    {
        return ResumeExportCrud::build(
            tableRenderer: ResumeExportLivewireTableRenderer::make(),
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
        return view('livewire.resume.export.resume-exports-table')
            ->with(['table' => $this->table()]);
    }
}

<?php

namespace App\Livewire\Resume\Certificates;

use App\Cruds\Schema\Certificates\CertificatesCrud;
use App\Cruds\Schema\Certificates\Renderers\CertificatesLivewireTableRenderer;
use App\Livewire\Concerns\IsLivewireTable;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class CertificatesTable extends Component
{
    use IsLivewireTable;

    /** @throws ModelNotFoundException */
    #[On('resume-updated')]
    #[Computed]
    public function getModels(): Collection
    {
        /** @var User $user */
        $user = Auth::user();

        $certificate = $user->certificates();

        return $certificate->get();
    }

    private function crud()
    {
        return CertificatesCrud::build(
            tableRenderer: CertificatesLivewireTableRenderer::make(),
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
        return view('livewire.resume.certificates.certificates-table')
            ->with(['table' => $this->table()]);
    }
}

<?php

namespace App\Livewire\Resume\Certificates;

use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteCertificate extends Component
{
    use IsLivewireForm;

    #[Locked]
    public int $certificateId;

    public function mount(int $certificateId): void
    {
        $this->certificateId = $certificateId;
    }

    public function deleteCertificate(): void
    {
        $user = $this->getUser();
        $id = $this->certificateId;
        $certificate = $user->certificates()->findOrFail($id);
        $certificate->delete();

        $this->dispatch('resume-updated');
    }

    private function getUser(): User
    {
        return Auth::user();
    }

    public function getComponent(): BackendComponent|CompoundComponent
    {
        return TableHelpers::livewireDeleteButton(
            action: 'deleteCertificate',
            confirmMessage: 'Are you sure you want to delete this certificate record?',
        );
    }

    public function render()
    {
        return view('livewire.resume.certificates.delete-certificate')
            ->with('component', $this->getComponent());
    }
}

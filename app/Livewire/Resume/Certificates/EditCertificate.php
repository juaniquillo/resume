<?php

namespace App\Livewire\Resume\Certificates;

use App\Actions\Resume\Certificate\UpdateCertificate;
use App\Cruds\Actions\General\FormatDateAction;
use App\Cruds\Schema\Certificates\CertificatesCrud;
use App\Cruds\Schema\Certificates\Renderers\CertificatesLivewireFormRenderer;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\Certificate;
use App\Models\User;
use Flux\FluxManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditCertificate extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $certificates = [];

    #[Locked]
    public int $certificateId;

    public function mount(int $certificateId): void
    {
        $this->certificateId = $certificateId;
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        $certificate = $this->getModel();

        $validator = $this->validateForm($this->crud($certificate)->make(), $this->certificates);

        (new UpdateCertificate(
            $validator->validated(),
            $certificate
        ))->handle();

        session()->flash('success', 'Certificate updated successfully.');

        $this->dispatch('resume-updated');

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $certificate = $this->getModel();

        $output = $this->crud($certificate)->make()->execute(
            new FormatDateAction(
                model: $certificate,
            )
        );

        $this->certificates = $output->toArray();
    }

    /** @throws ModelNotFoundException */
    #[Computed]
    private function getModel(): Certificate
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Certificate $certificate */
        $certificate = $user->certificates()->findOrFail($this->certificateId);

        return $certificate;
    }

    private function crud(Certificate $certificate)
    {
        return CertificatesCrud::build(
            values: $this->certificates,
            errors: $this->formErrors,
            model: $certificate,
            formRenderer: CertificatesLivewireFormRenderer::make(),
        );
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud($this->getModel())
            ->form()
            ->setAttribute('wire:submit.prevent', 'updateForm()');
    }

    public function getModalKey(): string
    {
        return "edit-certificate-{$this->certificateId}";
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                'button' => $this->modalButton(
                    label: 'Edit',
                    id: $id,
                    icon: self::EDIT_ICON,
                    size: 'xs'
                ),
                'modal' => $this->modalComponent(
                    id: $id,
                    content: $form,
                    themes: ['modal' => 'lg'],
                ),
            ]);
    }

    public function render()
    {
        return view('livewire.resume.certificates.edit-certificate')
            ->with('update', $this->getModal());
    }
}

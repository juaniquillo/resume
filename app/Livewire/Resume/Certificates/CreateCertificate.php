<?php

namespace App\Livewire\Resume\Certificates;

use App\Actions\Resume\Certificate\StoreCertificate;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Schema\Certificates\CertificatesCrud;
use App\Cruds\Schema\Certificates\Renderers\CertificatesLivewireFormRenderer;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\User;
use App\Support\ResumeLimit;
use Flux\Flux;
use Flux\FluxManager;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CreateCertificate extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $certificates = [];

    public function mount(): void
    {
        $this->refreshVariables();
    }

    public function createForm(): void
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->certificates()->count() >= ResumeLimit::CERTIFICATES) {
            Flux::toast(heading: __('Error'), text: ResumeLimit::errorMessage(__('certificates'), ResumeLimit::CERTIFICATES), variant: 'danger');

            return;
        }

        $validator = $this->validateForm($this->crud()->make(), $this->certificates);

        (new StoreCertificate(
            $validator->validated(),
            $user
        ))->handle();

        Flux::toast(text: 'Certificate created successfully.', variant: 'success');

        $this->dispatch('resume-updated');

        $this->refreshVariables();

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $output = $this->crud()
            ->make()
            ->execute(
                (new NameValueAction(values: []))
                    ->setGlobalDefault('')
            );

        $this->certificates = $output->toArray();
    }

    private function crud()
    {
        return CertificatesCrud::build(
            values: $this->certificates,
            errors: $this->formErrors,
            formRenderer: CertificatesLivewireFormRenderer::make(),
        );
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud()
            ->form()
            ->setAttribute('wire:submit.prevent', 'createForm()');
    }

    public function getModalKey(): string
    {
        return 'create-certificate';
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                'button' => $this->modalButton(
                    label: 'Create Certificate',
                    id: $id,
                    variant: 'filled',
                    icon: self::CREATE_ICON,
                ),
                'modal' => $this->modalComponent(
                    id: $id,
                    content: $form,
                    themes: ['modal' => 'lg']
                ),
            ]);
    }

    public function render()
    {
        return view('livewire.resume.certificates.create-certificate')
            ->with('create', $this->getModal());
    }
}

<?php

namespace App\Livewire\Resume\Publications;

use App\Actions\Resume\Publication\StorePublication;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Schema\Publications\PublicationsCrud;
use App\Cruds\Schema\Publications\Renderers\PublicationsLivewireFormRenderer;
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

class CreatePublication extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $publications = [];

    public function mount(): void
    {
        $this->refreshVariables();
    }

    public function createForm(): void
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->publications()->count() >= ResumeLimit::PUBLICATIONS) {
            Flux::toast(heading: __('Error'), text: ResumeLimit::errorMessage(__('publications'), ResumeLimit::PUBLICATIONS), variant: 'danger');

            return;
        }

        $validator = $this->validateForm($this->crud()->make(), $this->publications);

        (new StorePublication(
            $validator->validated(),
            $user
        ))->handle();

        Flux::toast(text: 'Publication created successfully.', variant: 'success');

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

        $this->publications = $output->toArray();
    }

    private function crud()
    {
        return PublicationsCrud::build(
            values: $this->publications,
            errors: $this->formErrors,
            formRenderer: PublicationsLivewireFormRenderer::make(),
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
        return 'create-publication';
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                'button' => $this->modalButton(
                    label: 'Create Publication',
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
        return view('livewire.resume.publications.create-publication')
            ->with('create', $this->getModal());
    }
}

<?php

namespace App\Livewire\Resume\Awards;

use App\Actions\Resume\Award\StoreAward;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Schema\Awards\AwardsCrud;
use App\Cruds\Schema\Awards\Renderers\AwardsLivewireFormRenderer;
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

class CreateAward extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $awards = [];

    public function mount(): void
    {
        $this->refreshVariables();
    }

    public function createForm(): void
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->awards()->count() >= ResumeLimit::AWARDS) {
            Flux::toast(heading: __('Error'), text: ResumeLimit::errorMessage(__('awards'), ResumeLimit::AWARDS), variant: 'danger');

            return;
        }

        $validator = $this->validateForm($this->crud()->make(), $this->awards);

        (new StoreAward(
            $validator->validated(),
            $user
        ))->handle();

        Flux::toast(text: 'Award created successfully.', variant: 'success');

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

        $this->awards = $output->toArray();
    }

    private function crud()
    {
        return AwardsCrud::build(
            values: $this->awards,
            errors: $this->formErrors,
            formRenderer: AwardsLivewireFormRenderer::make(),
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
        return 'create-award';
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                'button' => $this->modalButton(
                    label: 'Create Award',
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
        return view('livewire.resume.awards.create-award')
            ->with('create', $this->getModal());
    }
}

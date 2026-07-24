<?php

namespace App\Livewire\Resume\Works;

use App\Actions\Resume\Work\CreateWork as CreateWorkAction;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Schema\Works\WorksCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\User;
use Flux\FluxManager;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CreateWork extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $works = [];

    public function mount(): void
    {
        $this->refreshVariables();
    }

    public function createForm(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $validator = $this->validateForm($this->crud()->make(), $this->works);

        (new CreateWorkAction(
            $validator->validated(),
            $user
        ))->handle();

        session()->flash('success', 'Work created successfully.');

        $this->dispatch('resume-updated');

        $this->refreshVariables();

        (new FluxManager)->modal($this->getModalKey())->close();

        // $this->redirect(route('dashboard.works'));
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $output = $this->crud()
            ->make()
            ->execute(
                (new NameValueAction(values: []))
                    ->setGlobalDefault('') // Set a global default value for all inputs
            );

        $this->works = $output->toArray();
    }

    private function crud()
    {
        /** @var User $user */
        $user = Auth::user();

        return WorksCrud::build(
            values: $this->works,
            errors: $this->formErrors,
        );
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud()
            ->formNarrow()
            ->setAttribute('wire:submit.prevent', 'createForm()');
    }

    public function getModalKey(): string
    {
        return 'create-work';
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                // From trait
                'button' => $this->modalButton(
                    label: 'Create Work',
                    id: $id,
                    variant: 'filled',
                    icon: self::CREATE_ICON,
                ),
                // From trait
                'modal' => $this->modalComponent(
                    id: $id,
                    content: $form,
                    themes: ['modal' => 'lg']
                ),
            ]);
    }

    public function render()
    {
        return view('livewire.resume.works.create_work')
            ->with('create', $this->getModal());
    }
}

<?php

namespace App\Livewire\Resume\Highlights;

use App\Actions\Highlights\CreateHighlight as CreateHighlightAction;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Squema\Highlights\HighlightsCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\Contracts\HighlightModel;
use App\Models\User;
use Flux\FluxManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CreateHighlight extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $highlights = [];

    #[Locked]
    public Model|HighlightModel|null $model = null;

    public function mount(Model|HighlightModel $model)
    {
        $this->refreshVariables();
        $this->model = $model;
    }

    private function crud()
    {
        return HighlightsCrud::build(
            [],
            errors: $this->formErrors,
        );
    }

    public function createForm(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $validator = $this->validateForm($this->crud()->make(), $this->highlights);

        (new CreateHighlightAction(
            $user,
            $this->model,
            $validator->validated(),
        ))->handle();

        session()->flash('success', 'Work created successfully.');

        $this->dispatch('resume-updated');

        $this->refreshVariables();

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    public function refreshVariables(): void
    {
        $output = $this->crud()
            ->make()
            ->execute(
                (new NameValueAction(values: []))
                    ->setGlobalDefault('') // Set a global default value for all inputs
            );

        $this->highlights = $output->toArray();

    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud()
            ->setLivewire()
            ->formWithTextareaSpanFull()
            ->setAttribute('wire:submit.prevent', 'createForm()');
    }

    public function getModalKey(): string
    {
        return 'create-highlight';
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                // From trait
                'button' => $this->modalButton(
                    label: 'Create Highlight',
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
        return view('livewire.resume.highlights.create_highlight')
            ->with('create', $this->getModal());
    }
}

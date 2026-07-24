<?php

namespace App\Livewire\Resume\Volunteers\Highlights;

use App\Actions\Highlights\CreateHighlight as CreateHighlightAction;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Schema\Highlights\HighlightsCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\User;
use App\Models\Volunteer;
use Flux\FluxManager;
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
    public Volunteer $volunteer;

    public function mount(Volunteer $volunteer)
    {
        $this->refreshVariables();
        $this->volunteer = $volunteer;
    }

    private function crud()
    {
        return HighlightsCrud::build(
            baseRoute: 'dashboard.volunteers.highlights',
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
            $this->volunteer,
            $validator->validated(),
        ))->handle();

        session()->flash('success', 'Highlight created successfully.');

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
                    ->setGlobalDefault('')
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
        return "create-volunteer-highlight-{$this->volunteer->id}";
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                'button' => $this->modalButton(
                    label: 'Create Highlight',
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
        return view('livewire.resume.highlights.create_highlight')
            ->with('create', $this->getModal());
    }
}

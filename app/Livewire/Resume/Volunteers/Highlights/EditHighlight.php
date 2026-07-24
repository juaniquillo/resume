<?php

namespace App\Livewire\Resume\Volunteers\Highlights;

use App\Actions\Highlights\UpdateHighlight;
use App\Cruds\Schema\Highlights\HighlightsCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\Highlight;
use App\Models\Volunteer;
use Flux\FluxManager;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class EditHighlight extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $highlights = [];

    #[Locked]
    public int $highlightId;

    public function mount(int $highlightId): void
    {
        $this->highlightId = $highlightId;
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        $highlight = $this->getModel();
        $user = Auth::user();

        $validator = $this->validateForm($this->crud($highlight)->make(), $this->highlights);

        (new UpdateHighlight(
            $user,
            $highlight,
            $validator->validate(),
        ))->handle();

        session()->flash('success', 'Highlight updated successfully.');

        $this->dispatch('resume-updated');

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $this->highlights = $this->getModel()->toArray();
    }

    #[Computed]
    private function getModel(): Highlight
    {
        $user = Auth::user();
        $highlight = Highlight::findOrFail($this->highlightId);

        /** @var Volunteer $parent */
        $parent = $highlight->highlightable;

        if ($parent->user_id !== $user->id) {
            throw new AuthenticationException('You are not authorized to edit this highlight');
        }

        return $highlight;
    }

    private function crud(Highlight $highlight)
    {
        return HighlightsCrud::build(
            values: $this->highlights,
            errors: $this->formErrors,
            model: $highlight,
            baseRoute: 'dashboard.volunteers.highlights',
        )->setLivewire();
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud($this->getModel())
            ->formWithTextareaSpanFull()
            ->setAttribute('wire:submit.prevent', 'updateForm()');
    }

    public function getModalKey(): string
    {
        return "edit-volunteer-highlight-{$this->highlightId}";
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
        return view('livewire.resume.highlights.update_highlight')
            ->with('update', $this->getModal());
    }
}

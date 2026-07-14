<?php

namespace App\Livewire\Resume\Highlights;

use App\Actions\Highlights\UpdateHighlight;
use App\Cruds\Squema\Highlights\HighlightsCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\Contracts\HighlightModel;
use App\Models\Highlight;
use App\Models\User;
use Flux\FluxManager;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class EditHighlight extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $highlights = [];
    
    #[Locked]
    public ?int $highlightId = null;

    public function mount(int $highlightId): void
    {
        $this->highlightId = $highlightId;
        $this->refreshVariables();
    }
    
    public function updateForm(): void
    {
        $user = $this->getUser();
        $highlight = Highlight::findOrFail($this->highlightId);

        $validator = $this->validateForm($this->crud($highlight)->make(), $this->highlights);

        (new UpdateHighlight(
            $user,
            $highlight,
            $validator->validate(),
        ))->handle();

        session()->flash('success', 'Work updated successfully.');

        $this->dispatch('resume-updated');

        (new FluxManager)->modal($this->getModalKey())->close();
        
    }

    
    #[Computed]
    public function refreshVariables(): void
    {
        $this->highlights = $this->getModel()->toArray();
    }

    /** 
     * @throws ModelNotFoundException 
     */
    #[Computed]
    private function getModel(): Highlight
    {
        $user = $this->getUser();
        $highlight = Highlight::findOrFail($this->highlightId);

        /** @var Model|HighlightModel $parent */
        $parent = $highlight->highlightable;

        if($parent->getUserId() !== $user->id) {
            throw new AuthenticationException('You are not authorized to delete this highlight');
        }

        return $highlight;
    }

    private function getUser(): User
    {
        return Auth::user();
    }


    private function crud(Highlight $highlight)
    {
        return (HighlightsCrud::build(
            values: $this->highlights,
            errors: $this->formErrors,
            model: $highlight,
        ))
            ->setLivewire();
    }

    public function getForm(): BackendComponent|CompoundComponent
    {
        return $this->crud($this->getModel())
            ->formWithTextareaSpanFull()
            ->setAttribute('wire:submit.prevent', 'updateForm()');
    }
    
    public function getModalKey(): string
    {
        return "edit-work-{$this->highlightId}";
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                // From trait
                'button' => $this->modalButton(
                    label: 'Edit',
                    id: $id,
                    icon: self::EDIT_ICON,
                    size: 'xs'
                ),
                // From trait
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

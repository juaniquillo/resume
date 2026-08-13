<?php

namespace App\Livewire\Resume\Publications;

use App\Actions\Resume\Publication\UpdatePublication;
use App\Cruds\Actions\General\FormatDateAction;
use App\Cruds\Schema\Publications\PublicationsCrud;
use App\Cruds\Schema\Publications\Renderers\PublicationsLivewireFormRenderer;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\Publication;
use App\Models\User;
use Flux\Flux;
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

class EditPublication extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $publications = [];

    #[Locked]
    public int $publicationId;

    public function mount(int $publicationId): void
    {
        $this->publicationId = $publicationId;
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        $publication = $this->getModel();

        $validator = $this->validateForm($this->crud($publication)->make(), $this->publications);

        (new UpdatePublication(
            $validator->validated(),
            $publication
        ))->handle();

        Flux::toast(text: 'Publication updated successfully.', variant: 'success');

        $this->dispatch('resume-updated');

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $publication = $this->getModel();

        $output = $this->crud($publication)->make()->execute(
            new FormatDateAction(
                model: $publication,
            )
        );

        $this->publications = $output->toArray();
    }

    /** @throws ModelNotFoundException */
    #[Computed]
    private function getModel(): Publication
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Publication $publication */
        $publication = $user->publications()->findOrFail($this->publicationId);

        return $publication;
    }

    private function crud(Publication $publication)
    {
        return PublicationsCrud::build(
            values: $this->publications,
            errors: $this->formErrors,
            model: $publication,
            formRenderer: PublicationsLivewireFormRenderer::make(),
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
        return "edit-publication-{$this->publicationId}";
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
        return view('livewire.resume.publications.edit-publication')
            ->with('update', $this->getModal());
    }
}

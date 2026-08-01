<?php

namespace App\Livewire\Resume\Awards;

use App\Actions\Resume\Award\UpdateAward;
use App\Cruds\Actions\General\FormatDateAction;
use App\Cruds\Schema\Awards\AwardsCrud;
use App\Cruds\Schema\Awards\Renderers\AwardsLivewireFormRenderer;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\Award;
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

class EditAward extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $awards = [];

    #[Locked]
    public int $awardId;

    public function mount(int $awardId): void
    {
        $this->awardId = $awardId;
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        $award = $this->getModel();

        $validator = $this->validateForm($this->crud($award)->make(), $this->awards);

        (new UpdateAward(
            $validator->validated(),
            $award
        ))->handle();

        session()->flash('success', 'Award updated successfully.');

        $this->dispatch('resume-updated');

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $award = $this->getModel();

        $output = $this->crud($award)->make()->execute(
            new FormatDateAction(
                model: $award,
            )
        );

        $this->awards = $output->toArray();
    }

    /** @throws ModelNotFoundException */
    #[Computed]
    private function getModel(): Award
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Award $award */
        $award = $user->awards()->findOrFail($this->awardId);

        return $award;
    }

    private function crud(Award $award)
    {
        return AwardsCrud::build(
            values: $this->awards,
            errors: $this->formErrors,
            model: $award,
            formRenderer: AwardsLivewireFormRenderer::make(),
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
        return "edit-award-{$this->awardId}";
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
        return view('livewire.resume.awards.edit-award')
            ->with('update', $this->getModal());
    }
}

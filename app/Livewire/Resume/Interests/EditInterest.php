<?php

namespace App\Livewire\Resume\Interests;

use App\Actions\Resume\Interest\UpdateInterest;
use App\Cruds\Schema\Interests\Inputs\KeywordsFactory;
use App\Cruds\Schema\Interests\InterestsCrud;
use App\Cruds\Schema\Interests\Renderers\InterestsLivewireFormRenderer;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\Interest;
use App\Models\User;
use App\Support\RequestUtils;
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

class EditInterest extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $interests = [];

    #[Locked]
    public int $interestId;

    public function mount(int $interestId): void
    {
        $this->interestId = $interestId;
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        $interest = $this->getModel();

        $values = $this->interests;

        /** Convert keywords to array */
        $keywordsName = KeywordsFactory::NAME;
        $keywords = $values[$keywordsName] ?? null;
        if (! is_array($keywords)) {
            $processedKeywords = RequestUtils::commaSeparatedToArray($keywords);
            $values[$keywordsName] = $processedKeywords;
        }

        $validator = $this->validateForm($this->crud($interest)->make(), $values);

        (new UpdateInterest(
            $validator->validated(),
            $interest
        ))->handle();

        session()->flash('success', 'Interest updated successfully.');

        $this->dispatch('resume-updated');

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $interest = $this->getModel();
        $array = $interest->toArray();

        // Convert keywords array to comma-separated string for form input if needed,
        // or let KeywordsFactory/import handle it.
        $keywords = $array[KeywordsFactory::NAME] ?? [];
        if (is_array($keywords)) {
            $array[KeywordsFactory::NAME] = implode(', ', $keywords);
        }

        $this->interests = $array;
    }

    /** @throws ModelNotFoundException */
    #[Computed]
    private function getModel(): Interest
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Interest $interest */
        $interest = $user->interests()->findOrFail($this->interestId);

        return $interest;
    }

    private function crud(Interest $interest)
    {
        return InterestsCrud::build(
            values: $this->interests,
            errors: $this->formErrors,
            model: $interest,
            formRenderer: InterestsLivewireFormRenderer::make(),
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
        return "edit-interest-{$this->interestId}";
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
        return view('livewire.resume.interests.edit-interest')
            ->with('update', $this->getModal());
    }
}

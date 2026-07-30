<?php

namespace App\Livewire\Resume\Skills;

use App\Actions\Resume\Skill\UpdateSkill;
use App\Cruds\Schema\Skills\Inputs\KeywordsFactory;
use App\Cruds\Schema\Skills\Renderers\SkillsLivewireFormRenderer;
use App\Cruds\Schema\Skills\SkillsCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\Skill;
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

class EditSkill extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $skills = [];

    #[Locked]
    public int $skillId;

    public function mount(int $skillId): void
    {
        $this->skillId = $skillId;
        $this->refreshVariables();
    }

    public function updateForm(): void
    {
        $skill = $this->getModel();

        $values = $this->skills;

        /** Convert keywords to array */
        $keywordsName = KeywordsFactory::NAME;
        $keywords = $values[$keywordsName] ?? null;
        if (! is_array($keywords)) {
            $processedKeywords = RequestUtils::commaSeparatedToArray($keywords);
            $values[$keywordsName] = $processedKeywords;
        }

        $validator = $this->validateForm($this->crud($skill)->make(), $values);

        (new UpdateSkill(
            $validator->validated(),
            $skill
        ))->handle();

        session()->flash('success', 'Skill updated successfully.');

        $this->dispatch('resume-updated');

        (new FluxManager)->modal($this->getModalKey())->close();
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $this->skills = $this->getModel()->toArray();
    }

    /** @throws ModelNotFoundException */
    #[Computed]
    private function getModel(): Skill
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Skill $skill */
        $skill = $user->skills()->findOrFail($this->skillId);

        return $skill;
    }

    private function crud(Skill $skill)
    {
        return SkillsCrud::build(
            values: $this->skills,
            errors: $this->formErrors,
            model: $skill,
            formRenderer: SkillsLivewireFormRenderer::make(),
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
        return "edit-skill-{$this->skillId}";
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
        return view('livewire.resume.skills.edit-skill')
            ->with('update', $this->getModal());
    }
}

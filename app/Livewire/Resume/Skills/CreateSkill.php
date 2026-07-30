<?php

namespace App\Livewire\Resume\Skills;

use App\Actions\Resume\Skill\CreateSkill as CreateSkillAction;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Schema\Skills\Inputs\KeywordsFactory;
use App\Cruds\Schema\Skills\Renderers\SkillsLivewireFormRenderer;
use App\Cruds\Schema\Skills\SkillsCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\User;
use App\Support\RequestUtils;
use Flux\FluxManager;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CreateSkill extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $skills = [];

    public function mount(): void
    {
        $this->refreshVariables();
    }

    public function createForm(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $values = $this->skills;

        /** Convert keywords to array */
        $keywordsName = KeywordsFactory::NAME;
        $keywords = $values[$keywordsName] ?? null;
        if (! is_array($keywords)) {
            $processedKeywords = RequestUtils::commaSeparatedToArray($keywords);
            $values[$keywordsName] = $processedKeywords;
        }

        $validator = $this->validateForm($this->crud()->make(), $values);

        (new CreateSkillAction(
            $validator->validated(),
            $user
        ))->handle();

        session()->flash('success', 'Skill created successfully.');

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

        $this->skills = $output->toArray();
    }

    private function crud()
    {
        return SkillsCrud::build(
            values: $this->skills,
            errors: $this->formErrors,
            formRenderer: SkillsLivewireFormRenderer::make(),
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
        return 'create-skill';
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                'button' => $this->modalButton(
                    label: 'Create Skill',
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
        return view('livewire.resume.skills.create-skill')
            ->with('create', $this->getModal());
    }
}

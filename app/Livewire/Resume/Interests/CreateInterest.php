<?php

namespace App\Livewire\Resume\Interests;

use App\Actions\Resume\Interest\StoreInterest;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Schema\Interests\Inputs\KeywordsFactory;
use App\Cruds\Schema\Interests\InterestsCrud;
use App\Cruds\Schema\Interests\Renderers\InterestsLivewireFormRenderer;
use App\Livewire\Concerns\IsLivewireForm;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\User;
use App\Support\RequestUtils;
use Flux\Flux;
use Flux\FluxManager;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CreateInterest extends Component
{
    use IsLivewireForm,
        IsLivewireModal;

    public array $interests = [];

    public function mount(): void
    {
        $this->refreshVariables();
    }

    public function createForm(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $values = $this->interests;

        /** Convert keywords to array */
        $keywordsName = KeywordsFactory::NAME;
        $keywords = $values[$keywordsName] ?? null;
        if (! is_array($keywords)) {
            $processedKeywords = RequestUtils::commaSeparatedToArray($keywords);
            $values[$keywordsName] = $processedKeywords;
        }

        $validator = $this->validateForm($this->crud()->make(), $values);

        (new StoreInterest(
            $validator->validated(),
            $user
        ))->handle();

        Flux::toast(text: 'Interest created successfully.', variant: 'success');

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

        $this->interests = $output->toArray();
    }

    private function crud()
    {
        return InterestsCrud::build(
            values: $this->interests,
            errors: $this->formErrors,
            formRenderer: InterestsLivewireFormRenderer::make(),
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
        return 'create-interest';
    }

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();
        $form = $this->getForm();

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                'button' => $this->modalButton(
                    label: 'Create Interest',
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
        return view('livewire.resume.interests.create-interest')
            ->with('create', $this->getModal());
    }
}

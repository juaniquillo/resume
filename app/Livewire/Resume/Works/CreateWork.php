<?php

namespace App\Livewire\Resume\Works;

use App\Actions\Resume\Work\CreateWork as CreateWorkAction;
use App\Cruds\Actions\General\NameValueAction;
use App\Cruds\Squema\Works\WorksCrud;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CreateWork extends Component
{
    use IsLivewireForm;

    public array $works = [];

    public function mount(): void
    {
        $this->refreshVariables();
    }

    public function createForm(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $validator = $this->validateForm($this->crud()->make(), $this->works);

        (new CreateWorkAction(
            $validator->validated(),
            $user
        ))->handle();

        session()->flash('success', 'Work created successfully.');

        $this->redirect(route('dashboard.works'));
    }

    #[Computed]
    public function refreshVariables(): void
    {
        $output = $this->crud()
            ->make()
            ->execute(
                (new NameValueAction(values: []))
                    ->setGlobalDefault('') // Set a global default value for all inputs
            );

        $this->works = $output->toArray();
    }

    private function crud()
    {
        /** @var User $user */
        $user = Auth::user();

        return WorksCrud::build(
            values: $this->works,
            errors: $this->formErrors,
        );
    }

    public function render()
    {
        $crud = $this->crud();

        $form = $crud->formWithTextareaSpanFull()
            ->setAttribute('wire:submit.prevent', 'createForm()');

        return view('livewire.resume.works.create_work')
            ->with('form', $form);
    }
}

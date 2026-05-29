<?php

namespace App\Livewire\Resume;

use App\Actions\Resume\ResetResumeAction;
use App\Cruds\Squema\ResumeReset\ResumeResetCrud;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ResetResume extends Component
{
    public bool $confirming = false;

    public function resetResume(): void
    {
        /** @var User $user */
        $user = Auth::user();

        (new ResetResumeAction($user))->handle();

        session()->flash('success', __('Your resume has been completely reset.'));

        $this->confirming = false;

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        $crud = ResumeResetCrud::build();

        $form = $crud->form()
            ->setAttribute('wire:submit.prevent', 'resetResume');

        return view('livewire.resume.reset-resume', [
            'form' => $form,
        ]);
    }
}

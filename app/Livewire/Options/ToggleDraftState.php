<?php

namespace App\Livewire\Options;

use App\Models\User;
use App\Presenters\Cache\ResumePresenterCacheManager;
use App\Support\Helpers;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ToggleDraftState extends Component
{
    public function toggle(): void
    {
        /** @var User $user */
        $user = Auth::user();
        
        $options = $user->generalOptions;

        if (! $options) {
            $options = $user->generalOptions()->create([
                'is_draft' => true,
            ]);
        }

        $options->update([
            'is_draft' => ! $options->is_draft,
        ]);

        (new ResumePresenterCacheManager($user))->clearCache();

        $this->dispatch('resume-updated');
        
        $status = Helpers::isResumeInDraftState($user) ? __('Draft') : __('Published');
        
        $this->dispatch('toast', 
            variant: 'success',
            heading: __('Status Updated'),
            text: __('Resume status changed to :status', ['status' => $status]),
        );
    }

    public function render()
    {
        /** @var User $user */
        $user = Auth::user();
        $options = $user->generalOptions;
        
        return view('livewire.options.toggle-draft-state', [
            'isDraft' => Helpers::isResumeInDraftState($user),
            'slug' => $options?->slug,
        ]);
    }
}

<?php

namespace App\Actions\Resume;

use App\Models\User;
use App\Presenters\ResumePresenter;
use Illuminate\Support\Facades\DB;

class ResetResumeAction
{
    public function __construct(
        private User $user
    ) {}

    public function handle(): void
    {
        DB::transaction(function () {
            // Content sections
            $this->user->basics?->delete();
            
            $this->user->works()->each(fn($m) => $m->delete());
            $this->user->volunteers()->each(fn($m) => $m->delete());
            $this->user->education()->each(fn($m) => $m->delete());
            $this->user->awards()->each(fn($m) => $m->delete());
            $this->user->certificates()->each(fn($m) => $m->delete());
            $this->user->publications()->each(fn($m) => $m->delete());
            $this->user->skills()->each(fn($m) => $m->delete());
            $this->user->languages()->each(fn($m) => $m->delete());
            $this->user->interests()->each(fn($m) => $m->delete());
            $this->user->references()->each(fn($m) => $m->delete());
            $this->user->projects()->each(fn($m) => $m->delete());
        });

        // Clear cache
        (new ResumePresenter($this->user))->clearCache();
    }
}

<?php

namespace App\Presenters\Resume;

use App\Models\User;

final class ResumeDataLoader
{
    public function load(User $user): ResumeData
    {
        return new ResumeData(
            basics: $user->basics()->with(['location', 'profiles'])->first(),
            works: $user->works()->with('highlights')->orderByDesc('starts_at')->get(),
            volunteers: $user->volunteers()->with('highlights')->orderByDesc('starts_at')->get(),
            education: $user->education()->with('courses')->orderByDesc('starts_at')->get(),
            awards: $user->awards()->orderByDesc('awarded_at')->get(),
            certificates: $user->certificates()->orderByDesc('date')->get(),
            publications: $user->publications()->orderByDesc('date')->get(),
            skills: $user->skills()->get(),
            languages: $user->languages()->get(),
            interests: $user->interests()->get(),
            references: $user->references()->get(),
            projects: $user->projects()->with('highlights')->orderByDesc('start_date')->get(),
        );
    }
}

<?php

namespace App\Presenters\Resume;

use App\Models\Award;
use App\Models\Basic;
use App\Models\Certificate;
use App\Models\Education;
use App\Models\Interest;
use App\Models\Language;
use App\Models\Project;
use App\Models\Publication;
use App\Models\Reference;
use App\Models\Skill;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\Work;
use Illuminate\Database\Eloquent\Collection;

final class ResumeDataLoader
{
    public function load(User $user): ResumeData
    {
        /** @var Basic|null $basics */
        $basics = $user->basics()->with(['location', 'profiles'])->first();

        /** @var Collection<int, Work> $works */
        $works = $user->works()->with('highlights')->orderByDesc('starts_at')->get();

        /** @var Collection<int, Volunteer> $volunteers */
        $volunteers = $user->volunteers()->with('highlights')->orderByDesc('starts_at')->get();

        /** @var Collection<int, Education> $education */
        $education = $user->education()->with('courses')->orderByDesc('starts_at')->get();

        /** @var Collection<int, Award> $awards */
        $awards = $user->awards()->orderByDesc('awarded_at')->get();

        /** @var Collection<int, Certificate> $certificates */
        $certificates = $user->certificates()->orderByDesc('date')->get();

        /** @var Collection<int, Publication> $publications */
        $publications = $user->publications()->orderByDesc('date')->get();

        /** @var Collection<int, Skill> $skills */
        $skills = $user->skills()->get();

        /** @var Collection<int, Language> $languages */
        $languages = $user->languages()->get();

        /** @var Collection<int, Interest> $interests */
        $interests = $user->interests()->get();

        /** @var Collection<int, Reference> $references */
        $references = $user->references()->get();

        /** @var Collection<int, Project> $projects */
        $projects = $user->projects()->with('highlights')->orderByDesc('start_date')->get();

        return new ResumeData(
            basics: $basics,
            works: $works,
            volunteers: $volunteers,
            education: $education,
            awards: $awards,
            certificates: $certificates,
            publications: $publications,
            skills: $skills,
            languages: $languages,
            interests: $interests,
            references: $references,
            projects: $projects,
        );
    }
}

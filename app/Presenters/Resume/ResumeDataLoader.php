<?php

namespace App\Presenters\Resume;

use App\Enums\ProcessStatus;
use App\Models\Award;
use App\Models\Basic;
use App\Models\Certificate;
use App\Models\Education;
use App\Models\Interest;
use App\Models\Language;
use App\Models\Project;
use App\Models\Publication;
use App\Models\Reference;
use App\Models\ResumeExport;
use App\Models\Skill;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\Work;
use Closure;
use Illuminate\Database\Eloquent\Collection;

final class ResumeDataLoader
{
    private array $cache = [];

    public function load(User $user): ResumeData
    {
        return $this->getCached($user->id, 'full_resume', function () use ($user) {
            $user->load([
                'basics' => fn ($q) => $q->with(['location', 'profiles']),
                'works' => fn ($q) => $q->with('highlights')->orderByDesc('starts_at'),
                'volunteers' => fn ($q) => $q->with('highlights')->orderByDesc('starts_at'),
                'education' => fn ($q) => $q->with('courses')->orderByDesc('starts_at'),
                'awards' => fn ($q) => $q->orderByDesc('awarded_at'),
                'certificates' => fn ($q) => $q->orderByDesc('date'),
                'publications' => fn ($q) => $q->orderByDesc('date'),
                'skills',
                'languages',
                'interests',
                'references',
                'projects' => fn ($q) => $q->with('highlights')->orderByDesc('start_date'),
                'resumeExports' => fn ($q) => $q->where('allow_download', true)->where('status', ProcessStatus::COMPLETED),
            ]);

            return new ResumeData(
                basics: $user->basics instanceof Basic ? $user->basics : null,
                works: $user->works,
                volunteers: $user->volunteers,
                education: $user->education,
                awards: $user->awards,
                certificates: $user->certificates,
                publications: $user->publications,
                skills: $user->skills,
                languages: $user->languages,
                interests: $user->interests,
                references: $user->references,
                projects: $user->projects,
                downloads: $user->resumeExports,
            );
        });
    }

    public function basics(User $user): ?Basic
    {
        return $this->getCached($user->id, 'basics', function () use ($user) {
            if ($user->relationLoaded('basics')) {
                return $user->basics;
            }

            return $user->basics()->with(['location', 'profiles'])->first();
        });
    }

    /** @return Collection<int, Work> */
    public function works(User $user): Collection
    {
        return $this->getCached($user->id, 'works', function () use ($user) {
            if ($user->relationLoaded('works')) {
                return $user->works;
            }

            return $user->works()->with('highlights')->orderByDesc('starts_at')->get();
        });
    }

    /** @return Collection<int, Volunteer> */
    public function volunteers(User $user): Collection
    {
        return $this->getCached($user->id, 'volunteers', function () use ($user) {
            if ($user->relationLoaded('volunteers')) {
                return $user->volunteers;
            }

            return $user->volunteers()->with('highlights')->orderByDesc('starts_at')->get();
        });
    }

    /** @return Collection<int, Education> */
    public function education(User $user): Collection
    {
        return $this->getCached($user->id, 'education', function () use ($user) {
            if ($user->relationLoaded('education')) {
                return $user->education;
            }

            return $user->education()->with('courses')->orderByDesc('starts_at')->get();
        });
    }

    /** @return Collection<int, Award> */
    public function awards(User $user): Collection
    {
        return $this->getCached($user->id, 'awards', function () use ($user) {
            if ($user->relationLoaded('awards')) {
                return $user->awards;
            }

            return $user->awards()->orderByDesc('awarded_at')->get();
        });
    }

    /** @return Collection<int, Certificate> */
    public function certificates(User $user): Collection
    {
        return $this->getCached($user->id, 'certificates', function () use ($user) {
            if ($user->relationLoaded('certificates')) {
                return $user->certificates;
            }

            return $user->certificates()->orderByDesc('date')->get();
        });
    }

    /** @return Collection<int, Publication> */
    public function publications(User $user): Collection
    {
        return $this->getCached($user->id, 'publications', function () use ($user) {
            if ($user->relationLoaded('publications')) {
                return $user->publications;
            }

            return $user->publications()->orderByDesc('date')->get();
        });
    }

    /** @return Collection<int, Skill> */
    public function skills(User $user): Collection
    {
        return $this->getCached($user->id, 'skills', function () use ($user) {
            if ($user->relationLoaded('skills')) {
                return $user->skills;
            }

            return $user->skills()->get();
        });
    }

    /** @return Collection<int, Language> */
    public function languages(User $user): Collection
    {
        return $this->getCached($user->id, 'languages', function () use ($user) {
            if ($user->relationLoaded('languages')) {
                return $user->languages;
            }

            return $user->languages()->get();
        });
    }

    /** @return Collection<int, Interest> */
    public function interests(User $user): Collection
    {
        return $this->getCached($user->id, 'interests', function () use ($user) {
            if ($user->relationLoaded('interests')) {
                return $user->interests;
            }

            return $user->interests()->get();
        });
    }

    /** @return Collection<int, Reference> */
    public function references(User $user): Collection
    {
        return $this->getCached($user->id, 'references', function () use ($user) {
            if ($user->relationLoaded('references')) {
                return $user->references;
            }

            return $user->references()->get();
        });
    }

    /** @return Collection<int, Project> */
    public function projects(User $user): Collection
    {
        return $this->getCached($user->id, 'projects', function () use ($user) {
            if ($user->relationLoaded('projects')) {
                return $user->projects;
            }

            return $user->projects()->with('highlights')->orderByDesc('start_date')->get();
        });
    }

    /** @return Collection<int, ResumeExport> */
    public function downloads(User $user): Collection
    {
        return $this->getCached($user->id, 'downloads', function () use ($user) {
            if ($user->relationLoaded('resumeExports')) {
                return $user->resumeExports;
            }

            return $user->resumeExports()
                ->where('allow_download', true)
                ->where('status', ProcessStatus::COMPLETED)
                ->get();
        });
    }

    public function clearCache(?int $userId = null): void
    {
        if ($userId) {
            unset($this->cache[$userId]);

            return;
        }

        $this->cache = [];
    }

    private function getCached(int $userId, string $key, Closure $callback): mixed
    {
        if (! isset($this->cache[$userId][$key])) {
            $this->cache[$userId][$key] = $callback();
        }

        return $this->cache[$userId][$key];
    }
}

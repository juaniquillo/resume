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
        return $this->getCached($user->id, 'full_resume', fn () => new ResumeData(
            basics: $this->basics($user),
            works: $this->works($user),
            volunteers: $this->volunteers($user),
            education: $this->education($user),
            awards: $this->awards($user),
            certificates: $this->certificates($user),
            publications: $this->publications($user),
            skills: $this->skills($user),
            languages: $this->languages($user),
            interests: $this->interests($user),
            references: $this->references($user),
            projects: $this->projects($user),
            downloads: $this->downloads($user),
        ));
    }

    public function basics(User $user): ?Basic
    {
        return $this->getCached($user->id, 'basics', fn () => $user->basics()->with(['location', 'profiles'])->first());
    }

    /** @return Collection<int, Work> */
    public function works(User $user): Collection
    {
        return $this->getCached($user->id, 'works', fn () => $user->works()->with('highlights')->orderByDesc('starts_at')->get());
    }

    /** @return Collection<int, Volunteer> */
    public function volunteers(User $user): Collection
    {
        return $this->getCached($user->id, 'volunteers', fn () => $user->volunteers()->with('highlights')->orderByDesc('starts_at')->get());
    }

    /** @return Collection<int, Education> */
    public function education(User $user): Collection
    {
        return $this->getCached($user->id, 'education', fn () => $user->education()->with('courses')->orderByDesc('starts_at')->get());
    }

    /** @return Collection<int, Award> */
    public function awards(User $user): Collection
    {
        return $this->getCached($user->id, 'awards', fn () => $user->awards()->orderByDesc('awarded_at')->get());
    }

    /** @return Collection<int, Certificate> */
    public function certificates(User $user): Collection
    {
        return $this->getCached($user->id, 'certificates', fn () => $user->certificates()->orderByDesc('date')->get());
    }

    /** @return Collection<int, Publication> */
    public function publications(User $user): Collection
    {
        return $this->getCached($user->id, 'publications', fn () => $user->publications()->orderByDesc('date')->get());
    }

    /** @return Collection<int, Skill> */
    public function skills(User $user): Collection
    {
        return $this->getCached($user->id, 'skills', fn () => $user->skills()->get());
    }

    /** @return Collection<int, Language> */
    public function languages(User $user): Collection
    {
        return $this->getCached($user->id, 'languages', fn () => $user->languages()->get());
    }

    /** @return Collection<int, Interest> */
    public function interests(User $user): Collection
    {
        return $this->getCached($user->id, 'interests', fn () => $user->interests()->get());
    }

    /** @return Collection<int, Reference> */
    public function references(User $user): Collection
    {
        return $this->getCached($user->id, 'references', fn () => $user->references()->get());
    }

    /** @return Collection<int, Project> */
    public function projects(User $user): Collection
    {
        return $this->getCached($user->id, 'projects', fn () => $user->projects()->with('highlights')->orderByDesc('start_date')->get());
    }

    /** @return Collection<int, ResumeExport> */
    public function downloads(User $user): Collection
    {
        return $this->getCached($user->id, 'downloads', fn () => $user->resumeExports()
            ->where('allow_download', true)
            ->where('status', ProcessStatus::COMPLETED)
            ->get());
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

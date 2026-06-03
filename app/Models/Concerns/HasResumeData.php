<?php

namespace App\Models\Concerns;

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
use App\Models\Volunteer;
use App\Models\Work;
use App\Presenters\Resume\ResumeDataLoader;
use Illuminate\Database\Eloquent\Collection;

trait HasResumeData
{
    public function resumeBasics(): ?Basic
    {
        return app(ResumeDataLoader::class)->basics($this);
    }

    /** @return Collection<int, Work> */
    public function resumeWorks(): Collection
    {
        return app(ResumeDataLoader::class)->works($this);
    }

    /** @return Collection<int, Volunteer> */
    public function resumeVolunteers(): Collection
    {
        return app(ResumeDataLoader::class)->volunteers($this);
    }

    /** @return Collection<int, Education> */
    public function resumeEducation(): Collection
    {
        return app(ResumeDataLoader::class)->education($this);
    }

    /** @return Collection<int, Award> */
    public function resumeAwards(): Collection
    {
        return app(ResumeDataLoader::class)->awards($this);
    }

    /** @return Collection<int, Certificate> */
    public function resumeCertificates(): Collection
    {
        return app(ResumeDataLoader::class)->certificates($this);
    }

    /** @return Collection<int, Publication> */
    public function resumePublications(): Collection
    {
        return app(ResumeDataLoader::class)->publications($this);
    }

    /** @return Collection<int, Skill> */
    public function resumeSkills(): Collection
    {
        return app(ResumeDataLoader::class)->skills($this);
    }

    /** @return Collection<int, Language> */
    public function resumeLanguages(): Collection
    {
        return app(ResumeDataLoader::class)->languages($this);
    }

    /** @return Collection<int, Interest> */
    public function resumeInterests(): Collection
    {
        return app(ResumeDataLoader::class)->interests($this);
    }

    /** @return Collection<int, Reference> */
    public function resumeReferences(): Collection
    {
        return app(ResumeDataLoader::class)->references($this);
    }

    /** @return Collection<int, Project> */
    public function resumeProjects(): Collection
    {
        return app(ResumeDataLoader::class)->projects($this);
    }

    /** @return Collection<int, ResumeExport> */
    public function resumeDownloads(): Collection
    {
        return app(ResumeDataLoader::class)->downloads($this);
    }
}

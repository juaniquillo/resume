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
use App\Models\Volunteer;
use App\Models\Work;
use Illuminate\Database\Eloquent\Collection;

final class ResumeData
{
    /**
     * @param  Collection<int, Work>  $works
     * @param  Collection<int, Volunteer>  $volunteers
     * @param  Collection<int, Education>  $education
     * @param  Collection<int, Award>  $awards
     * @param  Collection<int, Certificate>  $certificates
     * @param  Collection<int, Publication>  $publications
     * @param  Collection<int, Skill>  $skills
     * @param  Collection<int, Language>  $languages
     * @param  Collection<int, Interest>  $interests
     * @param  Collection<int, Reference>  $references
     * @param  Collection<int, Project>  $projects
     */
    public function __construct(
        public ?Basic $basics,
        public Collection $works,
        public Collection $volunteers,
        public Collection $education,
        public Collection $awards,
        public Collection $certificates,
        public Collection $publications,
        public Collection $skills,
        public Collection $languages,
        public Collection $interests,
        public Collection $references,
        public Collection $projects,
    ) {}
}

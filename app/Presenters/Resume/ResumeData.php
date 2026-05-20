<?php

namespace App\Presenters\Resume;

use App\Models\Basic;
use Illuminate\Database\Eloquent\Collection;

final class ResumeData
{
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

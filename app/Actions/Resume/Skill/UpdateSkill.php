<?php

namespace App\Actions\Resume\Skill;

use App\Models\Skill;

class UpdateSkill
{
    public function __construct(
        private array $data,
        private Skill $skill
    ) {}

    public function handle(): bool
    {
        return $this->skill->update($this->data);
    }
}

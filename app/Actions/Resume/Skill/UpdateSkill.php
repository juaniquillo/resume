<?php

namespace App\Actions\Resume\Skill;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Skill;

class UpdateSkill
{
    public function __construct(
        private array $data,
        private Skill $skill
    ) {}

    public function handle(): bool
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        return $this->skill->update($data);
    }
}

<?php

namespace App\Actions\Resume\Skill;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Skill;
use App\Models\User;

class CreateSkill
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Skill
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        /** @var Skill */
        return $this->user->skills()->create($data);
    }
}

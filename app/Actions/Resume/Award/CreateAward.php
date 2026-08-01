<?php

namespace App\Actions\Resume\Award;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Award;
use App\Models\User;

class CreateAward
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Award
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        /** @var Award */
        return $this->user->awards()->create($data);
    }
}

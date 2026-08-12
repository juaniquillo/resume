<?php

namespace App\Actions\Resume\CoverLetter;

use App\Cruds\Helpers\FormHelpers;
use App\Models\User;

class StoreCoverLetter
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle()
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        return $this->user->coverLetters()->create($data);
    }
}

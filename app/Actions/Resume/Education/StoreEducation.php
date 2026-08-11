<?php

namespace App\Actions\Resume\Education;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Education;
use App\Models\User;

class StoreEducation
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Education
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        /** @var Education */
        return $this->user->education()->create($data);
    }
}

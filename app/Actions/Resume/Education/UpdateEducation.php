<?php

namespace App\Actions\Resume\Education;

use App\Models\Education;

class UpdateEducation
{
    public function __construct(
        private array $data,
        private Education $education
    ) {}

    public function handle(): bool
    {
        return $this->education->update($this->data);
    }
}

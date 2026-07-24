<?php

namespace App\Actions\Resume\CoverLetter;

use App\Cruds\Helpers\FormHelpers;
use App\Models\CoverLetter;

class UpdateCoverLetter
{
    public function __construct(
        private array $data,
        private CoverLetter $coverLetter
    ) {}

    public function handle(): bool
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        return $this->coverLetter->update($data);
    }
}

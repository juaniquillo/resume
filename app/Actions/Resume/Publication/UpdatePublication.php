<?php

namespace App\Actions\Resume\Publication;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Publication;

class UpdatePublication
{
    public function __construct(
        private array $data,
        private Publication $publication
    ) {}

    public function handle(): bool
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        return $this->publication->update($data);
    }
}

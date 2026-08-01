<?php

namespace App\Actions\Resume\Award;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Award;

class UpdateAward
{
    public function __construct(
        private array $data,
        private Award $award
    ) {}

    public function handle(): bool
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        return $this->award->update($data);
    }
}

<?php

namespace App\Actions\Resume\Reference;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Reference;

class UpdateReference
{
    public function __construct(
        private array $data,
        private Reference $reference
    ) {}

    public function handle(): bool
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        return $this->reference->update($data);
    }
}

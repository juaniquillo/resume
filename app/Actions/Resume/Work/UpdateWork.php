<?php

namespace App\Actions\Resume\Work;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Work;

class UpdateWork
{
    public function __construct(
        private array $data,
        private Work $work
    ) {}

    public function handle(): bool
    {
        /** Brings back null for empty strings for Livewire forms */
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        return $this->work->update($data);
    }
}




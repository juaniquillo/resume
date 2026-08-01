<?php

namespace App\Actions\Resume\Language;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Language;

class UpdateLanguage
{
    public function __construct(
        private array $data,
        private Language $language
    ) {}

    public function handle(): bool
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        return $this->language->update($data);
    }
}

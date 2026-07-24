<?php

namespace App\Actions\Resume\Language;

use App\Models\Language;

class UpdateLanguage
{
    public function __construct(
        private array $data,
        private Language $language
    ) {}

    public function handle(): bool
    {
        return $this->language->update($this->data);
    }
}

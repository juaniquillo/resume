<?php

namespace App\Services\ResumeImport\Processors;

use App\Actions\Options\UpdateGeneralOptions;
use App\Models\User;

class MetaProcessor
{
    public function process(User $user, array $data, bool $applyMetaOptions = true): void
    {
        if (! $applyMetaOptions) {
            return;
        }

        $version = $data['meta']['version'] ?? '1.0.0';
        $this->migrateIfNeeded($version, $data);

        if (! isset($data['meta']['options']) || ! is_array($data['meta']['options'])) {
            return;
        }

        $optionsData = $data['meta']['options'];

        // not the place to update the slug
        unset($optionsData['slug']);

        (new UpdateGeneralOptions($user, $optionsData))->handle();
    }

    protected function migrateIfNeeded(string $version, array &$data): void
    {
        // Future schema migration hooks based on export version can be added here
    }
}

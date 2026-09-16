<?php

namespace App\Services\ResumeImport\Processors;

use App\Cruds\Schema\Options\GeneralOptionsCrud;
use App\Models\GeneralOption;
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

        $allowedKeys = array_keys(GeneralOptionsCrud::build()->inputsArray());
        $optionsInputsKeys = array_keys(GeneralOptionsCrud::build()->optionsInputsArray());
        $allowedKeys = array_merge($allowedKeys, $optionsInputsKeys);
        $allowedKeys[] = 'theme';
        $allowedKeys[] = 'is_draft';

        $optionsData = collect($data['meta']['options'])
            ->only($allowedKeys)
            ->toArray();

        // Remove ID or user_id if present in exported options
        unset($optionsData['id'], $optionsData['user_id'], $optionsData['created_at'], $optionsData['updated_at']);

        if (! empty($optionsData)) {
            $existing = GeneralOption::where('user_id', $user->id)->first();
            if ($existing) {
                $existing->update($optionsData);
            } else {
                $optionsData['user_id'] = $user->id;
                if (! isset($optionsData['slug'])) {
                    $optionsData['slug'] = \Illuminate\Support\Str::slug($user->name).'-'.\Illuminate\Support\Str::random(6);
                }
                GeneralOption::create($optionsData);
            }
        }
    }

    protected function migrateIfNeeded(string $version, array &$data): void
    {
        // Future schema migration hooks based on export version can be added here
    }
}

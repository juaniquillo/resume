<?php

namespace App\Actions\Options;

use App\Cruds\Actions\General\FilterUnsetValuesAction;
use App\Cruds\Schema\Options\GeneralOptionsCrud;
use App\Models\User;

class UpdateGeneralOptions
{
    public function __construct(
        private User $user,
        private array $data
    ) {}

    public function handle(): void
    {
        $output = GeneralOptionsCrud::build()
            ->make()
            ->execute(
                new FilterUnsetValuesAction($this->data)
            );

        $payload = $output->toArray();

        if (! empty($payload)) {
            $this->user->generalOptions->update($payload);
        }
    }
}

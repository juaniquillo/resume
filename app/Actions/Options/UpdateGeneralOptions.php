<?php

namespace App\Actions\Options;

use App\Cruds\Actions\General\FilterUnsetValuesAction;
use App\Cruds\Helpers\FormHelpers;
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
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        $output = GeneralOptionsCrud::build()
            ->make()
            ->execute(
                new FilterUnsetValuesAction($data)
            );

        $payload = $output->toArray();

        if (! empty($payload)) {
            $this->user->generalOptions->update($payload);
        }
    }
}

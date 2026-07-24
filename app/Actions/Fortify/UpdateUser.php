<?php

namespace App\Actions\Fortify;

use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUser implements UpdatesUserProfileInformation
{
    use ProfileValidationRules;

    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, $this->updateValidationRules())->validate();

        if (isset($input['name'])) {
            $user->update(['name' => $input['name']]);
        }
    }

    public function updateValidationRules(): array
    {
        return [
            'name' => $this->nameRules(),
        ];
    }
}




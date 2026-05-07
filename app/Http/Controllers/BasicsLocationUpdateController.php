<?php

namespace App\Http\Controllers;

use App\Actions\Resume\Basics\UpdateLocation;
use App\Http\Requests\LocationsFormRequest;
use App\Models\Basic;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class BasicsLocationUpdateController extends Controller
{
    public function __invoke(LocationsFormRequest $request): RedirectResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        /** @var Basic|null $basics */
        $basics = $user?->basics()->first();

        if (! $basics) {
            return back()
                ->with('custom_error', __('basics.errors.basics_not_found'));
        }

        (new UpdateLocation($request->validated(), $basics))->handle();

        return back()
            ->with('success', 'Location created successfully.');
    }
}

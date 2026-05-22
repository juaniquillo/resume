<?php

namespace App\Http\Controllers\Options;

use App\Actions\Options\UpdateGeneralOptions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Options\GeneralOptionsFormRequest;

class GeneralOptionsUpdateController extends Controller
{
    public function __invoke(GeneralOptionsFormRequest $request)
    {
        (new UpdateGeneralOptions(
            $request->user(),
            $request->validated()
        ))->handle();

        return redirect()
            ->back()
            ->with('success', 'General options updated successfully.');
    }
}

<?php

namespace App\Http\Controllers\Options;

use App\Actions\Options\UpdateSectionVisibility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Options\SectionVisibilityFormRequest;

class SectionVisibilityUpdateController extends Controller
{
    public function __invoke(SectionVisibilityFormRequest $request)
    {
        (new UpdateSectionVisibility(
            $request->user(),
            $request->validated()
        ))->handle();

        return redirect()
            ->back()
            ->with('success', 'Section visibility updated successfully.');
    }
}

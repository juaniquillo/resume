<?php

namespace App\Http\Controllers\Options;

use App\Actions\Options\UpdateUserTheme;
use App\Http\Controllers\Controller;
use App\Http\Requests\Options\ResumeThemeFormRequest;

class ResumeThemeUpdateController extends Controller
{
    public function __invoke(ResumeThemeFormRequest $request)
    {
        (new UpdateUserTheme(
            $request->user(),
            $request->validated()['theme']
        ))->handle();

        return redirect()
            ->back()
            ->with('success', 'Resume theme updated successfully.');
    }
}

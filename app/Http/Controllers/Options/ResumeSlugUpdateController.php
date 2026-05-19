<?php

namespace App\Http\Controllers\Options;

use App\Actions\Options\UpdateUserSlug;
use App\Http\Controllers\Controller;
use App\Http\Requests\Options\UserSlugFormRequest;

class ResumeSlugUpdateController extends Controller
{
    public function __invoke(UserSlugFormRequest $request)
    {
        (new UpdateUserSlug(
            $request->user(),
            $request->validated('slug')
        ))->handle();

        return redirect()
            ->back()
            ->with('success', 'Resume slug updated successfully.');
    }
}

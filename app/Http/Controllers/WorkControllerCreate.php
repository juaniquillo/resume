<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkFormRequest;
use App\Models\User;

class WorkControllerCreate extends Controller
{
    public function __invoke(WorkFormRequest $request)
    {
        $validated = $request->validated();

        $user = User::find($request->user()->id);

        $user->works()->create($validated);

        return redirect()
            ->back()->with('success', 'Work created successfully.');
    }
}

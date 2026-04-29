<?php

namespace App\Http\Controllers;

use App\Actions\Resume\Basics\UpdateBasics;
use App\Cruds\Squema\Basics\Inputs\ImageFactory;
use App\Http\Requests\BasicsFormRequest;

class BasicsUpdateController extends Controller
{
    public function __invoke(BasicsFormRequest $request)
    {
        (new UpdateBasics(
            $request->validated(),
            $request->user(),
            $request->file(ImageFactory::NAME)
        ))->handle();

        return redirect()
            ->back()
            ->with('success', 'Basics information updated successfully.');
    }
}

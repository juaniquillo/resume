<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Basics\Inputs\ImageFactory;
use App\Http\Requests\BasicsFormRequest;
use App\Models\Basic;

class BasicsControllerCreate extends Controller
{
    public function __invoke(BasicsFormRequest $request)
    {
        $validated = $request->validated();

        // upload image
        if ($request->hasFile(ImageFactory::NAME)) {
            $image = $request->file('image');
            $path = $image->store('public/images');
            $validated['image'] = $path;
        }

        $basics = Basic::updateOrCreate(
            ['user_id' => auth()->user()->id],
            $validated
        );

        return redirect()->route('dashboard.basics')->with('success', 'Basics information updated successfully.');
    }
}

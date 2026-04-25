<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Basics\Inputs\ImageFactory;
use App\Http\Requests\BasicsFormRequest;
use App\Models\Basic;

class BasicsCreateController extends Controller
{
    public function __invoke(BasicsFormRequest $request)
    {
        $validated = $request->validated();

        dd($validated);

        // upload image
        if ($request->hasFile(ImageFactory::NAME)) {
            $image = $request->file(ImageFactory::NAME);
            $path = $image->store('public/images');
            dd($path);
            $validated['image'] = $path;
            /** @TODO Delete old image */
        } else {
            unset($validated['image']);
        }

        $basics = Basic::updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return redirect()
            ->back()
            ->with('success', 'Basics information updated successfully.');
    }
}

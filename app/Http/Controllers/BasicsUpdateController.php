<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Basics\Inputs\ImageFactory;
use App\Http\Requests\BasicsFormRequest;
use App\Models\Basic;
use Illuminate\Support\Facades\Storage;

class BasicsUpdateController extends Controller
{
    public function __invoke(BasicsFormRequest $request)
    {
        $validated = $request->validated();

        $basics = Basic::where('user_id', $request->user()->id)->first();

        // upload image
        if ($request->hasFile(ImageFactory::NAME)) {
            $image = $request->file(ImageFactory::NAME);

            if ($basics?->image) {
                Storage::delete($basics->image);
            }

            $path = Storage::putFile('basics', $image);
            $validated['image'] = $path;
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

<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;

class WorkHighlightsController extends Controller
{
    public function index(Request $request, int $id)
    {
        /** @var Work $work */
        $work = $request->user()->works()->findOrFail($id);

        return view('dashboard.works.highlights.index')
            ->with('model', $work);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Work;
use App\Support\ResumeLimit;
use Illuminate\Http\Request;

class WorkHighlightsController extends Controller
{
    public function __invoke(Request $request, int $id)
    {
        /** @var Work $work */
        $work = $request->user()->works()->findOrFail($id);

        return view('dashboard.works.highlights.index')
            ->with('model', $work)
            ->with('limit', ResumeLimit::HIGHLIGHTS);
    }
}

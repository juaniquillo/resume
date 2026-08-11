<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectHighlightsController extends Controller
{
    public function __invoke(Request $request, int $id)
    {
        /** @var Project $project */
        $project = $request->user()->projects()->findOrFail($id);

        return view('dashboard.projects.highlights.index')
            ->with('model', $project);
    }
}

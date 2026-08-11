<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use Illuminate\Http\Request;

class VolunteersHighlightsController extends Controller
{
    public function __invoke(Request $request, int $id)
    {
        /** @var Volunteer $volunteer */
        $volunteer = $request->user()->volunteers()->findOrFail($id);

        return view('dashboard.volunteers.highlights.index')
            ->with('volunteer', $volunteer);
    }
}

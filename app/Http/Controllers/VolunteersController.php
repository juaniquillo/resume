<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VolunteersController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.volunteers.index');
    }

    public function destroy(Request $request, int $id)
    {
        $model = $request->user()->volunteers()->findOrFail($id);

        $model->delete();

        return redirect()
            ->back()->with('success', 'Volunteer deleted successfully.');
    }
}

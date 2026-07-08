<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.works.index');
    }

    public function destroy(Request $request, int $id)
    {
        $model = $request->user()->works()->findOrFail($id);

        $model->delete();

        return redirect()
            ->back()->with('success', 'Work deleted successfully.');
    }
}

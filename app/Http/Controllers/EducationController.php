<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EducationController extends Controller
{
    public function index(Request $request): View
    {
        return view('dashboard.education.index');
    }

    public function destroy(Request $request, string $id): RedirectResponse
    {
        $user = $request->user();

        $education = $user->education()->findOrFail($id);

        $education->delete();

        return back()
            ->with('success', 'Education deleted successfully.');
    }
}

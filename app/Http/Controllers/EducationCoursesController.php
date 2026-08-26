<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Support\ResumeLimit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EducationCoursesController extends Controller
{
    public function __invoke(Request $request, int $id): View
    {
        /** @var Education $education */
        $education = $request->user()->education()->findOrFail($id);

        return view('dashboard.education.courses.index')
            ->with('education', $education)
            ->with('limit', ResumeLimit::COURSES);
    }
}

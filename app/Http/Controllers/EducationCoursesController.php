<?php

namespace App\Http\Controllers;

use App\Actions\Resume\Common\UpdateCourse;
use App\Cruds\Squema\Courses\CoursesCrud;
use App\Http\Requests\CoursesFormRequest;
use App\Models\Education;
use Illuminate\Http\Request;

class EducationCoursesController extends Controller
{
    public function index(Request $request, int $id)
    {
        /** @var Education $education */
        $education = $request->user()->education()->findOrFail($id);

        $crud = CoursesCrud::build(
            values: $request->old(),
            errors: $request->session()->get('errors')?->toArray() ?? [],
            baseRoute: 'dashboard.education.courses',
        );

        $crud->setFormAction(
            route('dashboard.education.courses.store', $id)
        );

        $table = null;
        $courses = $education->courses()->paginate(10);

        if (! $courses->isEmpty()) {
            $table = $crud->makeTable($courses);
        }

        return view('dashboard.education.courses.index')
            ->with('form', $crud->formWithTextareaSpanFull())
            ->with('table', $table)
            ->with('paginator', $courses);
    }

    public function store(CoursesFormRequest $request, int $id)
    {
        $validated = $request->validated();

        /** @var Education $education */
        $education = $request->user()->education()->findOrFail($id);
        $education->courses()->create($validated);

        return back()
            ->with('success', 'Course created successfully.');
    }

    public function edit(Request $request, int $id, int $courseId)
    {
        /** @var Education $education */
        $education = $request->user()->education()->findOrFail($id);
        $course = $education->courses()->findOrFail($courseId);
        $values = $request->old();
        $errors = $request->session()->get('errors')?->toArray() ?? [];

        $crud = CoursesCrud::build(
            values: $values,
            errors: $errors,
            model: $course,
            baseRoute: 'dashboard.education.courses',
        );

        $crud->setFormAction(
            route('dashboard.education.courses.update', [$id, $courseId])
        );

        $form = $crud->formWithTextareaSpanFull();

        return view('dashboard.education.courses.edit')
            ->with('form', $form)
            ->with('course', $course);
    }

    public function update(CoursesFormRequest $request, int $id, int $courseId)
    {
        /** @var Education $education */
        $education = $request->user()->education()->findOrFail($id);
        $course = $education->courses()->findOrFail($courseId);

        (new UpdateCourse($request->validated(), $course))->handle();

        return back()
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Request $request, int $id, int $courseId)
    {
        /** @var Education $education */
        $education = $request->user()->education()->findOrFail($id);
        $course = $education->courses()->findOrFail($courseId);

        $course->delete();

        return back()
            ->with('success', 'Course deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Basics\BasicsCrud;
use App\Cruds\Squema\Courses\CoursesCrud;
use App\Cruds\Squema\Education\EducationCrud;
use App\Cruds\Squema\Profiles\ProfilesCrud;
use App\Models\Basic;
use App\Models\Education;
use Illuminate\Http\Request;
use JustSteveKing\Resume\Exporters\MarkdownExporter;
use JustSteveKing\Resume\Factories\ResumeFactory;

class ResumeController extends Controller
{
    public function __invoke(Request $request)
    {
        $basicsModel = Basic::query()->find(1);

        // dd($basics->toArray());

        $markdown = null;

        if ($basicsModel) {

            $education = Education::query()
                ->where('user_id', $basicsModel->user_id)
                ->with('courses')
                ->get()
                ->map(fn (Education $edu) => [
                    ...$edu->toArray(),
                    CoursesCrud::NAME => $edu->courses->pluck('course')->toArray(),
                ]);

            $resume = ResumeFactory::fromArray([
                BasicsCrud::NAME => [
                    ...$basicsModel->toArray(),
                    ProfilesCrud::NAME => $basicsModel->profiles->toArray(),
                ],
                EducationCrud::NAME => $education->toArray(),
            ]);

            $exporter = new MarkdownExporter;

            $markdown = $exporter->export($resume);
        }

        // dd($resume);

        return view('resume')
            ->with('resume', $markdown);
    }
}

<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Basics\BasicsCrud;
use App\Cruds\Squema\Profiles\ProfilesCrud;
use App\Models\Basic;
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

            $resume = ResumeFactory::fromArray([
                BasicsCrud::NAME => [
                    ...$basicsModel->toArray(),
                    ProfilesCrud::NAME => $basicsModel->profiles->toArray(),
                ],
            ]);

            $exporter = new MarkdownExporter;

            $markdown = $exporter->export($resume);
        }

        // dd($resume);

        return view('resume')
            ->with('resume', $markdown);
    }
}

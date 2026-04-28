<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Basics\BasicsCrud;
use App\Cruds\Squema\Profiles\ProfilesCrud;
use App\Models\Basic;
use Illuminate\Http\Request;
use JustSteveKing\Resume\Exporters\MarkdownExporter;
use JustSteveKing\Resume\Factories\ResumeFactory;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('index');
    }
}

<?php

namespace App\Http\Controllers\Options;

use App\Cruds\Squema\Options\SectionVisibilityCrud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SectionVisibilityController extends Controller
{
    public function __invoke()
    {
        return view('dashboard.options.visibility');
    }
}


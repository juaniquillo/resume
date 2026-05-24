<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Cache\EmptyResumeCacheCrud;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ResumeCacheController extends Controller
{
    public function index(Request $request)
    {
        $crud = EmptyResumeCacheCrud::build();
        $crud->setFormAction(route('dashboard.resume.cache.store'));

        return view('dashboard.resume.cache')
            ->with('form', $crud->formWithTextareaSpanFull());
    }

    public function store(Request $request): RedirectResponse
    {
        $crud = EmptyResumeCacheCrud::build();
        $crud->handleCacheClear();

        return redirect()->back()->with('success', 'Cache cleared successfully.');
    }
}

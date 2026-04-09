<?php

namespace App\Http\Controllers;

use App\Cruds\Squema\Works\WorksCrud;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function index()
    {
        $form = WorksCrud::build()->formWithTextareaSpanFull();
        return view('dashboard.works.index')
            ->with('form', $form);
    }
}

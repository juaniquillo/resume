<?php

namespace App\Http\Controllers;

use App\Support\ResumeLimit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CertificatesController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('dashboard.certificates.index')
            ->with('limit', ResumeLimit::CERTIFICATES);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CertificatesController extends Controller
{
    public function index(Request $request): View
    {
        return view('dashboard.certificates.index');
    }
}

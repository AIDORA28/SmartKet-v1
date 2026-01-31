<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class RegistroController extends Controller
{
    public function registro(): View
    {
        return view('registro');
    }

    public function login(): View
    {
        return view('login');
    }
}


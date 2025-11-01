<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PacienteAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.paciente.login');
    }

    public function showRegister()
    {
        return view('auth.paciente.register');
    }
}

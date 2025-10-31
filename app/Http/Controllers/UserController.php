<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function store(Request $request)
    {
       // $admin = Auth::user();

        // Verificar rol del usuario autenticado
        //if ($admin->userType->nombre_tipo !== 'Administrador') {
            //return response()->json(['error' => 'No autorizado'], 403);
        //}

        // Validación
        $validated = $request->validate([
            'nombres' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'correo_electronico' => 'required|email|unique:users,correo_electronico',
            'password' => 'required|string|min:8|confirmed',
            'id_tipo_usuario' => 'required|in:2,3', // 2 = médico, 3 = secretaria
            'id_tipo_documento' => 'required|exists:document_type,id_tipo_documento',
            'numero_documento' => 'required|string|max:30|unique:users,numero_documento',
            'telefono' => 'nullable|string|max:20',
        ]);

        // Creación
        $user = User::create([
            ...$validated,
            'password' => Hash::make($validated['password']),
            'fecha_creacion_sistema' => now(),
        ]);

        return response()->json([
            'message' => 'Usuario creado con éxito',
            'user' => $user
        ]);
    }
}

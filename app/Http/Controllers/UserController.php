<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    
    public function create()
    {
        // Cargar vista Blade con formulario
        /*return view('admin.users.create');*/
    } //Acá iría el return view de la vista blade

    public function store(Request $request)
    {
       $admin = Auth::user();

        //Verificar rol del usuario autenticado
        if ($admin->userType->nombre_tipo !== 'Administrador') {
            return redirect()->back()->withErrors('No tienes permisos para crear usuarios.');
        }

        // Validación
        $validated = $request->validate([
            'nombres' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'correo_electronico' => 'required|email|unique:users,correo_electronico',
            'password' => 'required|string|min:8|confirmed',
            'id_tipo_usuario' => 'required|in:1,2,3', // 1=admin, 2=médico, 3=secretaria //modificado para que el admin pueda crear otros admins
            'id_tipo_documento' => 'required|exists:document_type,id_tipo_documento',
            'numero_documento' => 'required|string|max:30|unique:users,numero_documento',
            'telefono' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'fecha_ingreso_ips' => 'nullable|date',
            'observaciones' => 'nullable|string|max:255',
        ]);

        // Creación
        $user = User::create([
            ...$validated,
            'password' => Hash::make($validated['password']),
            'fecha_creacion_sistema' => now(),
        ]);

        
        /*return redirect()->back()->with('success', 'Usuario creado con éxito');*/ //Acá se redirige al componente de css de usuario creado
    }   
}

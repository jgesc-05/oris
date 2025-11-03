<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserType;
use App\Models\DocumentType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    
    public function create()
    {
        $tiposDocumento = DocumentType::all();
        $tiposUsuario = UserType::all();
    
        return view('admin.usuarios.create', compact('tiposDocumento', 'tiposUsuario'));
    }
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
            'password' => $validated['password'],
            'fecha_creacion_sistema' => now(),
        ]);

        
        return redirect()
        ->route('admin.usuarios.create')
        ->with('success', 'El usuario fue registrado correctamente.');
}

    //Inicio de sesión
    public function staffLogin(Request $request){
// 1. Validación
$credentials = $request->validate([
    'correo_electronico' => ['required', 'email'],
    'password' => ['required'],
]);

// 2. Búsqueda manual por la columna de tu BD
$user = User::where('correo_electronico', $credentials['correo_electronico'])->first();

// 3. Verificación de existencia y contraseña
if ($user && Hash::check($credentials['password'], $user->password)) {
    
    // 4. Inicio de sesión y seguridad de sesión
    Auth::login($user);
    $request->session()->regenerate();

    // 5. Redirección por rol (usando id_tipo_usuario)
    return $this->redirectToRole($user->id_tipo_usuario);
}

// 6. Retorno de Fallo 
return back()->withErrors([
    'correo_electronico' => 'Las credenciales no coinciden con nuestros registros.',
])->onlyInput('correo_electronico');
    }

    //Redireccionamiento a vista según el rol
    public function redirectToRole(int $roleId){
        switch ($roleId) {
            case 1:
                //Administrador
                return redirect()->intended(route('admin.dashboard'));
            case 2:
                //Médico
                /*return redirect()->intended('/supervisor/dashboard');*/
            case 3:
                //Secretaria
                /*return redirect()->intended('/staff/dashboard');*/
            default:
                Auth::logout(); 
                return redirect('/login')->withErrors(['error' => 'Rol de usuario inválido.']);
        }
    }

    //Vista de login
    public function viewStaffLogin(){
        return view('auth.login');
    }
}   


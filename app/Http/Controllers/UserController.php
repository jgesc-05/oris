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
        if ($admin->userType->id_tipo_usuario != 1) {
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

// 2. Búsqueda manual por la columna de la BD
$user = User::where('correo_electronico', $credentials['correo_electronico'])->first();

// 3. Verificación de existencia y contraseña
if ($user && Hash::check($credentials['password'], $user->password)) {
    
    // 4. Inicio de sesión y seguridad de sesión
    Auth::guard('web')->login($user);
    $request->session()->regenerate();

    $user->update([
        'ultimo_acceso' => now(),
    ]);

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
                /*return redirect()->intended(route(''));*/
            case 3:
                //Secretaria
                /*return redirect()->intended(route(''));*/
            default:
                Auth::logout(); 
                return redirect('/login')->withErrors(['error' => 'Rol de usuario inválido.']);
        }
    }

    //Vista de login
    public function viewStaffLogin(){
        return view('auth.login');
    }

    //Logout
    public function staffLogout(){
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect('/login');
    }

    //Obtención de usuarios empresariales dinámica, para mostrar en la tabla de usuarios (sección de admin)
    public function index(Request $request)
    {
        // Empezamos la consulta base
        $query = User::with('userType');
    
        // Filtro de búsqueda (nombre, apellidos, correo o documento)
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($subquery) use ($q) {
                $subquery->where('nombres', 'like', "%{$q}%")
                         ->orWhere('apellidos', 'like', "%{$q}%")
                         ->orWhere('correo_electronico', 'like', "%{$q}%")
                         ->orWhere('numero_documento', 'like', "%{$q}%");
            });
        }
    
        //  Filtro por rol
        if ($request->filled('rol')) {
            $query->whereHas('userType', function ($subquery) use ($request) {
                $subquery->where('nombre', $request->rol);
            });
        }
    
        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
    
        // Filtro por fecha de registro
        if ($request->filled('fecha')) {
            $today = now();
            switch ($request->fecha) {
                case 'hoy':
                    $query->whereDate('fecha_creacion_sistema', $today->toDateString());
                    break;
                case '7d':
                    $query->whereBetween('fecha_creacion_sistema', [$today->copy()->subDays(7), $today]);
                    break;
                case '30d':
                    $query->whereBetween('fecha_creacion_sistema', [$today->copy()->subDays(30), $today]);
                    break;
            }
        }
    
        // Ejecutar la consulta final
        $users = $query->orderBy('id_usuario', 'desc')
                       ->paginate(10)
                       ->withQueryString(); // mantiene los filtros al paginar
    
        // Devolvemos también los valores de los filtros al view
        return view('admin.usuarios.index', [
            'users' => $users,
            'filters' => $request->only(['q', 'rol', 'estado', 'fecha']),
        ]);
    }

    //Editar usuario existente (por parte del admin)
    public function edit(int $id)
    {
        $user = User::findOrFail($id);
        $tiposDocumento = DocumentType::all();
        $tiposUsuario = UserType::all();


        return view('admin.usuarios.edit', compact('user', 'tiposDocumento', 'tiposUsuario'));
    }
    
    //Actualizar usuario existente(admin)
    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nombres' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'correo_electronico' => 'required|email|unique:users,correo_electronico,' . $user->id_usuario . ',id_usuario',
            'id_tipo_usuario' => 'required|in:1,2,3',
            'id_tipo_documento' => 'required|exists:document_type,id_tipo_documento',
            'numero_documento' => 'required|string|max:30|unique:users,numero_documento,' . $user->id_usuario . ',id_usuario',
            'telefono' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'fecha_ingreso_ips' => 'nullable|date',
            'observaciones' => 'nullable|string|max:255',
        ]);

        // Si se proporciona una nueva contraseña, validarla y actualizarla
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $validated['password'] = $request->password;
        } else {
            // Mantener la contraseña actual si no se proporciona una nueva
            unset($validated['password']);
        }

        // Actualizar el usuario
        $user->update($validated);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'El usuario se actualizó correctamente.');
    }
}   
    


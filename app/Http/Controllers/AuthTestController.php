<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthTestController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo_electronico' => 'required|email',
            'password' => 'required'
        ]);
    
        $user = User::where('correo_electronico', $credentials['correo_electronico'])->first();
    
        if (! $user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
    
        if (! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Contraseña incorrecta'], 401);
        }
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
}

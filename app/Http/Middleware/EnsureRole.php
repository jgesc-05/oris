<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    protected array $rolesMap = [
        'admin'      => 1,
        'medico'     => 2,
        'secretaria' => 3,
    ];

    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $allowedRoles = collect($roles)
            ->map(function ($role) {
                return $this->rolesMap[$role] ?? (is_numeric($role) ? (int) $role : null);
            })
            ->filter()
            ->all();

        if (!in_array($user->id_tipo_usuario, $allowedRoles, true)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}

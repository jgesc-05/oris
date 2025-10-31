<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;



//Creación de usuarios por parte del administrador
Route::post('/admin/users', [UserController::class, 'store']);

Route::get('/test/404', function () {
    return response()->json(['message' => '¡Ruta de prueba API OK!']);
});
// **

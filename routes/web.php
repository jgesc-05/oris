<?php

use App\Http\Controllers\UserController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;

//Provisional
Route::get('/', function () {
    return view('welcome');
});



// Ruta para ver formulario de creación de usuario

Route::get('/admin/users/create', [UserController::class, 'create'])
    ->middleware('auth')
    ->name('admin.users.create');
 //Acá sería la ruta a la vista blade de creación de usuarios

// Ruta para guardar el usuario
Route::post('/admin/users', [UserController::class, 'store'])
    ->middleware('auth')
    ->name('admin.users.store');


 //provisional




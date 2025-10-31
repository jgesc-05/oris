<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Provisional
Route::get('/', function () {
    return view('welcome');
});

//Creación de usuarios por parte del administrador
Route::middleware(['auth'])->post('/admin/users', [UserController::class, 'store']);



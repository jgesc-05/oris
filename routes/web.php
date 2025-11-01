<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/test', 'test'); //Esto es una prueba
// Página Acceder
Route::view('/acceder', 'access.index')->name('acceder');

// Paciente: login y registro (mock)
Route::prefix('paciente')->name('paciente.')->group(function () {
    Route::view('/login', 'auth.paciente.login')->name('login');
    Route::view('/registro', 'auth.paciente.register')->name('register');
});

// Staff: login (mock si aún no lo tienes)
Route::view('/login', 'auth.login')->name('login');

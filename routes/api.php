<?php

use App\Http\Controllers\AuthTestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use GuzzleHttp\Psr7\Request;

//Creación de usuarios por parte del administrador
Route::post('/admin/users', [UserController::class, 'store']);
Route::post('/login', [AuthTestController::class, 'login']);
Route::middleware('auth:sanctum')->post('/users/admin', [UserController::class, 'store']);


<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/registro', [RegistroController::class, 'registro'])->name('registro');
Route::get('/login', [RegistroController::class, 'login'])->name('login');

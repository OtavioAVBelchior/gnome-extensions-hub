<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/github', [AuthController::class, 'redirect'])->name('login.github');
Route::get('/auth/github/callback', [AuthController::class, 'callback']);

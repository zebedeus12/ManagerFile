<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return view('/auth.login');
});

//login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/home', function () {
    return view('home');
})->middleware('auth');

//dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

//employees
Route::resource('employees', EmployeeController::class);

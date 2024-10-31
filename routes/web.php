<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
<<<<<<< HEAD
use App\Http\Controllers\FolderController;
=======
use App\Http\Controllers\MediaController;
>>>>>>> 3aed01cb6b63cadc82dd0ce32ee6825d7160a4a7


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
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//employees
Route::resource('employees', EmployeeController::class);

<<<<<<< HEAD
//folder
Route::resource('folders', FolderController::class);
Route::get('/folder/form', [FolderController::class, 'showForm'])->name('folder.form');
Route::post('/folder/store', [FolderController::class, 'store'])->name('folder.store');


=======
//media
Route::resource('media', MediaController::class);
>>>>>>> 3aed01cb6b63cadc82dd0ce32ee6825d7160a4a7

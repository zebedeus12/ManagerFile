<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\FileController;


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

//folder
Route::get('/file', [FileController::class, 'index'])->name('file.index');
Route::get('/files/create', [FileController::class, 'create'])->name('files.create');
Route::post('/files', [FileController::class, 'store'])->name('files.store');
Route::get('/folder/form', [FolderController::class, 'showForm'])->name('folder.form');
Route::post('/folder/store', [FolderController::class, 'store'])->name('folder.store');



//media
Route::resource('media', MediaController::class);
Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
Route::get('/media/{media}/edit', [MediaController::class, 'edit'])->name('media.edit');
Route::get('/media/search', [MediaController::class, 'search'])->name('media.search');



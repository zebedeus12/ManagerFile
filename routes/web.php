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

// Mengelola file
Route::get('/file', [FileController::class, 'index'])->name('file.index'); // Menampilkan halaman utama file manager
Route::get('/files/create', [FileController::class, 'create'])->name('files.create'); // Form upload file baru
Route::post('/files', [FileController::class, 'store'])->name('files.store'); // Menyimpan file baru
Route::post('/folder/{folder}/add-file', [FileController::class, 'store'])->name('folder.files.store'); // Menyimpan file baru di dalam folder tertentu

// Mengelola folder
Route::get('/folder/create/{parentId?}', [FolderController::class, 'showForm'])->name('folder.create'); // Form tambah folder
Route::post('/folder/store/{parentId?}', [FolderController::class, 'store'])->name('folder.store'); // Simpan folder baru
Route::get('/folder/{folder}', [FolderController::class, 'show'])->name('folder.show'); // Tampilkan folder tertentu

//media
Route::resource('media', MediaController::class);
Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
Route::get('/media/{media}/edit', [MediaController::class, 'edit'])->name('media.edit');
Route::get('/media/search', [MediaController::class, 'search'])->name('media.search');
Route::put('/media/{medium}', [MediaController::class, 'update'])->name('media.update');
Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');



<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MediaFolderController;
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
Route::get('/files', [FileController::class, 'index'])->name('file.index'); // Menampilkan halaman utama file manager
Route::get('/files/create/{folder?}', [FileController::class, 'create'])->name('files.create'); // Form upload file
Route::get('/files/{id}', [FileController::class, 'show'])->name('files.show'); // Detail file
Route::post('/files/{folder?}', [FileController::class, 'store'])->name('files.store');

// Mengelola folder
Route::get('/folder/create/{parentId?}', [FolderController::class, 'showForm'])->name('folder.create'); // Form tambah folder
Route::post('/folder/store/{parentId?}', [FolderController::class, 'store'])->name('folder.store'); // Simpan folder baru
Route::get('/folder/{folder}', [FolderController::class, 'show'])->name('folder.show'); // Tampilkan folder tertentu
Route::post('/folder/rename/{id}', [FolderController::class, 'rename'])->name('folder.rename');
Route::post('/folder/share/{id}', [FolderController::class, 'share'])->name('folder.share');
Route::delete('/folder/{id}', [FolderController::class, 'destroy'])->name('folder.destroy');
Route::post('/folder/{id}/copy', [FolderController::class, 'copy'])->name('folder.copy');
Route::delete('/folder/delete/{id}', [FolderController::class, 'destroy'])->name('folder.delete');




//media
Route::get('/media', [MediaController::class, 'index'])->name('media.index');
Route::get('/media/create', [MediaController::class, 'create'])->name('media.create');
Route::post('media/store', [MediaController::class, 'store'])->name('media.store');
Route::get('media/folder/{id}', [MediaController::class, 'index'])->name('media.folder.show');
Route::get('/media/{media}', [MediaController::class, 'show'])->name('media.show');
Route::get('/media/{media}/edit', [MediaController::class, 'edit'])->name('media.edit');
Route::put('/media/{media}', [MediaController::class, 'update'])->name('media.update');
Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
Route::get('/media/search', [MediaController::class, 'search'])->name('media.search');
Route::get('/media/folder/create/{parentId?}', [MediaFolderController::class, 'create'])->name('media.folder.create');
Route::post('/media/folder/store/{parentId?}', [MediaFolderController::class, 'store'])->name('media.folder.store');
Route::get('media/folder/{id}', [MediaFolderController::class, 'show'])->name('media.folder.show');
Route::put('/media/folder/{id}/rename', [MediaFolderController::class, 'rename'])->name('media.folder.rename');

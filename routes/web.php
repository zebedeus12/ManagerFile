<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MediaFolderController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PricingController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('/auth.login');
});

// Menampilkan form login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Menampilkan halaman OTP
Route::get('/otp', [LoginController::class, 'showOTPForm'])->name('otp.form');

// Verifikasi OTP
Route::post('/verify-otp', [LoginController::class, 'verifyOTP'])->name('verify.otp');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rute untuk autentikasi standar (melalui Auth::routes)
Auth::routes(); // Pastikan ini ada setelah rute login Anda

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


//princing
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');
Route::get('/pricing', function () {
    return view('pricing.index'); // View placeholder
})->name('pricing.index');

//employees
Route::resource('employees', EmployeeController::class);

// Mengelola file
Route::prefix('files')->group(function () {
    Route::get('/files', [FileController::class, 'index'])->name('file.index');
    Route::get('/create/{folder?}', [FileController::class, 'create'])->name('files.create'); // Form upload file
    Route::post('/store/{folder?}', [FileController::class, 'store'])->name('files.store'); // Menyimpan file
    Route::get('/{id}', [FileController::class, 'show'])->name('files.show'); // Menampilkan detail file
});
Route::delete('/files/delete/{fileId}', [FileController::class, 'destroy'])->name('file.delete');
// routes/web.php
Route::post('/file/rename/{fileId}', [FileController::class, 'rename'])->name('file.rename');
Route::get('/file/share/{fileId}', [FileController::class, 'share'])->name('file.share');

// Preview file
Route::get('/file/preview/{id}', [FileController::class, 'preview'])->name('files.preview');
// In routes/web.php
Route::post('files/delete-multiple', [FileController::class, 'deleteMultiple'])->name('files.deleteMultiple');

// Mengelola folder
Route::get('/folder/create/{parentId?}', [FolderController::class, 'showForm'])->name('folder.create'); // Form tambah folder
Route::post('/folder/store/{parentId?}', [FolderController::class, 'store'])->name('folder.store'); // Simpan folder baru
Route::get('/folder/{folder}', [FolderController::class, 'show'])->name('folder.show'); // Tampilkan folder tertentu
Route::post('/folder/rename/{id}', [FolderController::class, 'rename'])->name('folder.rename');
Route::post('/folder/share/{id}', [FolderController::class, 'share'])->name('folder.share');
Route::delete('/folder/{id}', [FolderController::class, 'destroy'])->name('folder.destroy');
Route::post('/folder/copy/{id}', [FolderController::class, 'copy'])->name('folder.copy');
Route::delete('/folder/delete/{id}', [FolderController::class, 'destroy'])->name('folder.delete');
Route::get('/folder/check/{id}', [FolderController::class, 'checkFolder']);
Route::post('/folders/deleteMultiple', [FolderController::class, 'deleteMultiple'])->name('folders.deleteMultiple');
Route::get('/folders', [FolderController::class, 'index'])->name('folder.index');


//mediacontroller
Route::get('/media', [MediaController::class, 'index'])->name('media.index');
Route::get('/media/create', [MediaController::class, 'create'])->name('media.create');
Route::post('media/store', [MediaController::class, 'store'])->name('media.store');
Route::get('media/folder/{id}', [MediaController::class, 'index'])->name('media.folder.show');
Route::get('/media/{media}', [MediaController::class, 'show'])->name('media.show');
Route::get('/media/{media}/edit', [MediaController::class, 'edit'])->name('media.edit');
Route::put('/media/{media}', [MediaController::class, 'update'])->name('media.update');
Route::delete('/media/folder/{id}', [MediaFolderController::class, 'destroy'])->name('media.folder.destroy');
// Route::delete('/media/{id}', [MediaController::class, 'destroy'])->name('media.destroy');
Route::get('/media/folder/check/{id}', [MediaFolderController::class, 'checkFolder']);
Route::get('/media/folders/search', [MediaController::class, 'searchFolders'])->name('media.folders.search');
Route::post('/media/folder/delete-multiple', [MediaFolderController::class, 'destroyMultiple'])
    ->name('media.folder.deleteMultiple');
Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
Route::delete('/media/delete/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

Route::get('media/{id}', [MediaController::class, 'show']);


//foldercontroller
Route::get('/media/folder/create/{parentId?}', [MediaFolderController::class, 'create'])->name('media.folder.create');
Route::post('/media/folder/store/{parentId?}', [MediaFolderController::class, 'store'])->name('media.folder.store');
Route::get('media/folder/{id}', [MediaFolderController::class, 'show'])->name('media.folder.show');
Route::put('/media/folder/{id}/rename', [MediaFolderController::class, 'rename'])->name('media.folder.rename');
Route::get('/folder/{id}/share', [MediaFolderController::class, 'share'])->name('media.folder.share');
Route::delete('/media/folder/{id}/delete', [MediaFolderController::class, 'destroy'])->name('media.folder.destroy');
Route::post('/media/folder/copy/{id}', [MediaFolderController::class, 'copy'])->name('media.folder.copy');

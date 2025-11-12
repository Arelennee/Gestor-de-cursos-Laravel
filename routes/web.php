<?php

use App\Http\Controllers\CursoController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\LeccionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('cursos', CursoController::class);
    Route::resource('cursos.lecciones', LeccionController::class)->shallow();
    Route::resource('cursos.inscripciones', InscripcionController::class)->shallow(); // Corregido 'crusos'

    // Grupo de rutas para Administradores
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
    });
});

require __DIR__.'/auth.php';

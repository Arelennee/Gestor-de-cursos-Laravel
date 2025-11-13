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

Route::get('/dashboard', function (Request $request) {
    $user = $request->user();
    $cursos = collect(); // Usar una colección vacía por defecto

    if ($user->esEstudiante()) {
        // Cargar los cursos inscritos con eager loading para optimizar
        $cursos = $user->cursosInscritos()->latest()->get();
    }

    return view('dashboard', ['cursos' => $cursos]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('cursos', CursoController::class);
    Route::resource('cursos.lecciones', LeccionController::class)->shallow();
    Route::resource('cursos.inscripciones', InscripcionController::class)->shallow(); // Corregido 'crusos'

    Route::post('/lecciones/{leccion}/complete', [LeccionController::class, 'complete'])->name('lecciones.complete');

    // Grupo de rutas para Administradores
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
    });
});
use App\Models\User;
use App\Models\Curso;

    Route::get('/debug-inscripcion/{user}/{curso}', function(User $user, Curso $curso) {
// Para usar esta ruta, asegúrate de haber iniciado sesión como admin
if (!auth()->check() || !auth()->user()->esAdmin()) {
     abort(403, 'Esta ruta de depuración es solo para administradores.');
   }

   $estaInscrito = $user->inscripciones()->where('curso_id', $curso->id)->exists();

    // La función dd() detiene la ejecución y muestra la información
   dd([
       'MENSAJE' => 'Resultado de la comprobación de inscripción',
      'ID del Usuario' => $user->id,
       'Nombre del Usuario' => $user->name,
      'ID del Curso' => $curso->id,
       'Título del Curso' => $curso->titulo,
       'ESTA INSCRITO (según la BD)' => $estaInscrito,
   ]);
 });


require __DIR__.'/auth.php';

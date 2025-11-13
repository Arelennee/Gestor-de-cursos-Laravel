<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Inscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InscripcionController extends Controller
{
    /**
     * Muestra una lista de inscripciones para un curso específico.
     * Solo accesible por el profesor del curso o un administrador.
     */
    public function index(Curso $curso)
    {
        // Autorizar: solo el profesor del curso o un admin pueden ver las inscripciones
        $this->authorize('update', $curso); // Reutilizamos la política de 'update' de CursoPolicy

        $inscripciones = $curso->inscripciones()->with('user')->get();

        // return view('inscripciones.index', compact('curso', 'inscripciones'));
        return "Listado de inscripciones para el curso: " . $curso->nombre . "\n" . $inscripciones
            ->pluck('user.name')
            ->implode(', ');
    }

    /**
     * No se usa directamente para crear, la inscripción se hace vía 'store'.
     */
    public function create(Curso $curso)
    {
        return redirect()->route('cursos.show', $curso)->with('error', 'Acción no permitida.');
    }

    /**
     * Inscribe al usuario autenticado en un curso.
     */
    public function store(Request $request, Curso $curso)
    {
        $user = Auth::user();

        // Verificar si el usuario ya está inscrito
        if ($curso->inscripciones()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Ya estás inscrito en este curso.');
        }

        // Crear la inscripción
        $curso->inscripciones()->create([
            'user_id' => $user->id,
            'fecha_inscripcion' => now(),
        ]);

        // return redirect()->route('cursos.show', $curso)->with('success', 'Te has inscrito exitosamente en el curso.');
        return "Inscripción exitosa en el curso: " . $curso->nombre;
    }

    /**
     * Muestra una inscripción específica.
     * Solo accesible por el estudiante inscrito, el profesor del curso o un administrador.
     */
    public function show(Inscripcion $inscripcion)
    {
        // Autorizar: el usuario debe ser el inscrito, el profesor del curso o un admin
        if (
            Auth::id() !== $inscripcion->user_id &&
            !Auth::user()->esAdmin() &&
            Auth::id() !== $inscripcion->curso->user_id
        ) {
            abort(403, 'No tienes permiso para ver esta inscripción.');
        }

        // return view('inscripciones.show', compact('inscripcion'));
        return "Detalles de la inscripción del usuario " .
            $inscripcion->user->name .
            " en el curso " .
            $inscripcion->curso->nombre;
    }

    /**
     * No se usa directamente para editar, la gestión se hace vía 'destroy' o por admin.
     */
    public function edit(Inscripcion $inscripcion)
    {
        return redirect()
            ->route('cursos.show', $inscripcion->curso)
            ->with('error', 'Acción no permitida.');
    }

    /**
     * No se usa directamente para actualizar.
     */
    public function update(Request $request, Inscripcion $inscripcion)
    {
        return redirect()
            ->route('cursos.show', $inscripcion->curso)
            ->with('error', 'Acción no permitida.');
    }

    /**
     * Elimina una inscripción (desinscribir).
     * Puede ser el propio estudiante o el profesor/admin del curso.
     */
    public function destroy(Inscripcion $inscripcion)
    {
        // Autorizar: el usuario debe ser el inscrito, el profesor del curso o un admin
        if (
            Auth::id() !== $inscripcion->user_id &&
            !Auth::user()->esAdmin() &&
            Auth::id() !== $inscripcion->curso->user_id
        ) {
            abort(403, 'No tienes permiso para desinscribir.');
        }

        $inscripcion->delete();

        // return redirect()->route('cursos.show', $inscripcion->curso)->with('success', 'Inscripción eliminada exitosamente.');
        return "Inscripción eliminada para el usuario " .
            $inscripcion->user->name .
            " del curso " .
            $inscripcion->curso->nombre;
    }
}

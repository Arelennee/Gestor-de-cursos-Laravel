<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Leccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LeccionController extends Controller
{
    public function create(Curso $curso)
    {
        // Asegurarse de que el usuario puede actualizar el curso (es profesor o admin)
        $this->authorize('update', $curso);

        // Aquí retornarías la vista para crear la lección
        // return view('lecciones.create', compact('curso'));
        return "Formulario para crear una lección para el curso: " . $curso->nombre;
    }

    /**
     * Almacena una nueva lección en la base de datos.
     */
    public function store(Request $request, Curso $curso)
    {
        $this->authorize('update', $curso);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
        ]);

        $curso->lecciones()->create([
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
        ]);

        // Redirigir a la página del curso con un mensaje
        // return redirect()->route('cursos.show', $curso)->with('success', 'Lección creada exitosamente.');
        return "Lección creada para el curso: " . $curso->nombre;
    }

    /**
     * Muestra una lección específica.
     */
    public function show(Leccion $leccion)
    {
        // Aquí retornarías la vista para mostrar la lección
        // return view('lecciones.show', compact('leccion'));
        return "Viendo la lección: " . $leccion->titulo;
    }

    /**
     * Muestra el formulario para editar una lección.
     */
    public function edit(Leccion $leccion)
    {
        // La autorización se basa en si el usuario puede actualizar el curso padre
        $this->authorize('update', $leccion->curso);

        // Aquí retornarías la vista para editar la lección
        // return view('lecciones.edit', compact('leccion'));
        return "Formulario para editar la lección: " . $leccion->titulo;
    }

    /**
     * Actualiza una lección específica en la base de datos.
     */
    public function update(Request $request, Leccion $leccion)
    {
        $this->authorize('update', $leccion->curso);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
        ]);

        $leccion->update($request->all());

        // Redirigir a la página del curso con un mensaje
        // return redirect()->route('cursos.show', $leccion->curso)->with('success', 'Lección actualizada exitosamente.');
        return "Lección actualizada: " . $leccion->titulo;
    }

    /**
     * Elimina una lección específica.
     */
    public function destroy(Leccion $leccion)
    {
        $this->authorize('update', $leccion->curso);

        $curso = $leccion->curso;
        $leccion->delete();

        // Redirigir a la página del curso con un mensaje
        // return redirect()->route('cursos.show', $curso)->with('success', 'Lección eliminada exitosamente.');
        return "Lección eliminada.";
    }
}

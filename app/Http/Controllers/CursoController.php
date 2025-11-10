<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Http\Requests\StoreCursoRequest; // Importar
use App\Http\Requests\UpdateCursoRequest; // Importar

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Curso::class);
        $cursos = Curso::all();
        return view('cursos.index', compact('cursos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Curso::class);
        return view('cursos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCursoRequest $request) // Cambiado
    {
        $this->authorize('create', Curso::class);

        $curso = Curso::create([
            'user_id' => auth()->id(),
            // Usamos validated() para obtener los datos ya validados
            ...$request->validated()
        ]);

        return redirect()->route('cursos.show', $curso)->with('success', 'Curso creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Curso $curso)
    {
        $this->authorize('view', $curso);
        return view('cursos.show', compact('curso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curso $curso)
    {
        $this->authorize('update', $curso);
        return view('cursos.edit', compact('curso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCursoRequest $request, Curso $curso) // Cambiado
    {
        $this->authorize('update', $curso);

        // Usamos validated() para obtener los datos ya validados
        $curso->update($request->validated());

        return redirect()->route('cursos.show', $curso)->with('success', 'Curso actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curso $curso)
    {
        $this->authorize('delete', $curso);
        $curso->delete();
        return redirect()->route('cursos.index')->with('success', 'Curso eliminado exitosamente.');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Curso;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Encontrar al usuario profesor
        $profesor = User::where('rol', User::ROL_PROFESOR)->first();

        if ($profesor) {
            // Crear un curso para el profesor
            $cursoLaravel = Curso::create([
                'user_id' => $profesor->id,
                'titulo' => 'Curso de Laravel desde Cero',
                'descripcion' => 'Aprende los fundamentos de Laravel para crear aplicaciones web modernas.',
            ]);

            // Añadir algunas lecciones al curso
            $cursoLaravel->lecciones()->createMany([
                ['titulo' => 'Introducción a Laravel', 'contenido' => 'Contenido de la lección 1...'],
                ['titulo' => 'Rutas y Controladores', 'contenido' => 'Contenido de la lección 2...'],
                ['titulo' => 'Vistas con Blade', 'contenido' => 'Contenido de la lección 3...'],
            ]);

            // Crear otro curso
            $cursoVue = Curso::create([
                'user_id' => $profesor->id,
                'titulo' => 'Curso de Vue.js',
                'descripcion' => 'Domina el framework progresivo de JavaScript.',
            ]);
             // Añadir algunas lecciones al curso
             $cursoVue->lecciones()->createMany([
                ['titulo' => 'Introducción a Vue', 'contenido' => 'Contenido de la lección 1...'],
                ['titulo' => 'Componentes y Props', 'contenido' => 'Contenido de la lección 2...'],
            ]);
        }
    }
}
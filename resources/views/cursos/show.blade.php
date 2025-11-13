{{--
    NOTA GENERAL:
    Este es un archivo Blade de Laravel. Utiliza la sintaxis de Blade (@extends, @if, {{ ... }}, etc.)
    que se compila a PHP plano en el servidor.
--}}

{{--
    @extends('layouts.app'):
    Esto le dice a Blade que esta vista "hereda" de una plantilla base.
    En este caso, hereda de `resources/views/layouts/app.blade.php`.
    La plantilla base define la estructura HTML principal (<html>, <head>, <body>, etc.),
    la barra de navegación y los scripts comunes.
    El contenido de esta vista se insertará en los "slots" definidos en la plantilla base.
--}}
<x-app-layout>
    {{--
        <x-slot name="header">:
        Este es un "slot" con nombre. El contenido dentro de este bloque
        se inyectará en la variable `$header` dentro de la plantilla `app.blade.php`.
        Es útil para definir contenido específico de la página en la cabecera.
    --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $curso->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-2xl font-semibold text-gray-800">{{ $curso->titulo }}</h3>
                        <p class="mt-2 text-gray-600">{{ $curso->descripcion }}</p>

                        <dl class="mt-4 grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-3">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Categoría</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $curso->categoria ?? 'No especificada' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Nivel</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $curso->nivel }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Duración</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $curso->duracion ? $curso->duracion . ' horas' : 'No especificada' }}</dd>
                            </div>
                        </dl>
                    </div>


                    <div class="mt-4 border-t pt-4">
                        {{--
                            AQUÍ EMPIEZA LA LÓGICA DE AUTORIZACIÓN EN LA VISTA
                            Mostraremos diferentes elementos de la interfaz según el rol y estado del usuario.
                        --}}

                        {{-- @auth ... @endauth: El contenido solo se muestra si el usuario ha iniciado sesión. --}}
                        @auth
                            {{--
                                @can('update', $curso) ... @endcan:
                                Esta directiva de Blade comprueba una Policy. En este caso, llama al método `update`
                                de `CursoPolicy` pasándole el `$curso` actual.
                                Devolverá `true` si el usuario es el profesor del curso o un administrador.
                            --}}
                            @can('update', $curso)
                                {{-- Si el usuario tiene permiso, mostramos los botones de gestión del curso. --}}
                                <a href="{{ route('cursos.edit', $curso) }}" class="text-blue-500 hover:text-blue-700">Editar Curso</a>
                                <a href="#" class="ml-4 text-green-500 hover:text-green-700">Añadir Lección</a>
                            @else
                                {{--
                                    Si el usuario no puede actualizar el curso, significa que es un estudiante.
                                    Ahora comprobamos si está inscrito o no.
                                --}}
                                @if(Auth::user()->cursosInscritos->contains($curso))
                                    {{--
                                        `Auth::user()->cursosInscritos` es la relación que definimos en el modelo User.
                                        `->contains($curso)` es un método de las colecciones de Laravel que comprueba si el curso actual
                                        está en la lista de cursos inscritos del usuario.
                                    --}}
                                    <p class="text-green-600 font-bold">Ya estás inscrito en este curso.</p>
                                    {{-- Aquí podrías añadir un formulario con un botón para desinscribirse, apuntando a la ruta `inscripciones.destroy`. --}}
                                @else
                                    {{-- Si no está inscrito, mostramos el botón para inscribirse. --}}
                                    <form action="{{ route('cursos.inscripciones.store', $curso) }}" method="POST">
                                        {{-- @csrf: Es una directiva de Blade crucial para la seguridad. Genera un token oculto para proteger contra ataques CSRF. --}}
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            Inscribirse al curso
                                        </button>
                                    </form>
                                @endif
                            @endcan
                        @endauth

                        {{-- @guest ... @endguest: El contenido solo se muestra si el usuario NO ha iniciado sesión (es un invitado). --}}
                        @guest
                            <p>Inicia sesión para ver las opciones de inscripción.</p>
                        @endguest
                    </div>

                    {{--
                        LISTA DE LECCIONES
                        Aquí mostramos las lecciones asociadas a este curso.
                    --}}
                    <div class="mt-6">
                        <h4 class="text-xl font-semibold">Lecciones</h4>
                        <ul class="mt-4 space-y-2">
                            @forelse ($curso->lecciones as $leccion)
                                <li class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                    <a href="{{ route('lecciones.show', $leccion) }}" class="text-gray-800 hover:text-indigo-600">
                                        {{ $leccion->titulo }}
                                    </a>
                                    @if(Auth::user()->leccionesCompletadas->contains($leccion))
                                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @endif
                                </li>
                            @empty
                                <li class="text-gray-500">Este curso aún no tiene lecciones.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

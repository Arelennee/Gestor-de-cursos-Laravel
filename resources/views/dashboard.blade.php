<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>{{ __("You're logged in!") }}</p>

                    {{-- Sección para Estudiantes: Mis Cursos --}}
                    @if(Auth::user()->esEstudiante())
                        <div class="mt-8">
                            <h3 class="text-2xl font-semibold text-gray-800">Mis Cursos</h3>
                            @if($cursos && $cursos->count() > 0)
                                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach ($cursos as $curso)
                                        @php
                                            $totalLecciones = $curso->lecciones->count();
                                            $leccionesCompletadas = Auth::user()->leccionesCompletadas->where('curso_id', $curso->id)->count();
                                            $porcentaje = $totalLecciones > 0 ? ($leccionesCompletadas / $totalLecciones) * 100 : 0;
                                            
                                            $color = 'bg-blue-600'; // 0%
                                            if ($porcentaje > 0 && $porcentaje < 100) {
                                                $color = 'bg-yellow-500'; // En progreso
                                            } elseif ($porcentaje == 100) {
                                                $color = 'bg-green-600'; // Completado
                                            }
                                        @endphp
                                        <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                                            <div class="p-6 flex-grow">
                                                <h4 class="font-semibold text-lg text-gray-800">{{ $curso->titulo }}</h4>
                                                <p class="text-gray-600 mt-2 text-sm">{{ Str::limit($curso->descripcion, 100) }}</p>
                                            </div>
                                            <div class="p-6 bg-gray-50">
                                                <div class="flex justify-between items-center mb-2">
                                                    <span class="text-sm font-medium text-gray-700">Progreso</span>
                                                    <span class="text-sm font-medium text-gray-700">{{ round($porcentaje) }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                    <div class="{{ $color }} h-2.5 rounded-full" style="width: {{ $porcentaje }}%"></div>
                                                </div>
                                                <div class="mt-4 flex justify-end">
                                                    <a href="{{ route('cursos.show', $curso) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                                        Ir al Curso
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="mt-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
                                    <p>Aún no te has inscrito a ningún curso. ¡Explora nuestro catálogo y empieza a aprender!</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

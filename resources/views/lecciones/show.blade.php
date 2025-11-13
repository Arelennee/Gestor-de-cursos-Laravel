<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $leccion->curso->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-semibold mb-4">{{ $leccion->titulo }}</h3>
                    
                    <div class="prose max-w-none">
                        {!! $leccion->contenido !!}
                    </div>

                    <div class="mt-8 border-t pt-6">
                        @if (Auth::user()->leccionesCompletadas->contains($leccion))
                            <div class="text-center">
                                <p class="text-lg font-semibold text-green-600">
                                    <svg class="inline-block w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Lección Completada
                                </p>
                            </div>
                        @else
                            <form action="{{ route('lecciones.complete', $leccion) }}" method="POST" class="text-center">
                                @csrf
                                <x-primary-button>
                                    {{ __('Marcar como completada') }}
                                </x-primary-button>
                            </form>
                        @endif
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('cursos.show', $leccion->curso) }}" class="text-indigo-600 hover:text-indigo-900">
                            &larr; Volver al curso
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

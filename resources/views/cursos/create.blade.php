<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Curso') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div class="p-6 text-gray-900">
                                        <form method="POST" action="{{ route('cursos.store') }}">
                                            @csrf
                
                                            <!-- Título -->
                                            <div>
                                                <x-input-label for="titulo" :value="__('Título')" />
                                                <x-text-input id="titulo" class="block mt-1 w-full" type="text" name="titulo" :value="old('titulo')" required autofocus />
                                                <x-input-error :messages="$errors->get('titulo')" class="mt-2" />
                                            </div>
                
                                            <!-- Descripción -->
                                            <div class="mt-4">
                                                <x-input-label for="descripcion" :value="__('Descripción')" />
                                                <textarea id="descripcion" name="descripcion" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('descripcion') }}</textarea>
                                                <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                                            </div>
                
                                            <!-- Categoría -->
                                            <div class="mt-4">
                                                <x-input-label for="categoria" :value="__('Categoría')" />
                                                <x-text-input id="categoria" class="block mt-1 w-full" type="text" name="categoria" :value="old('categoria')" />
                                                <x-input-error :messages="$errors->get('categoria')" class="mt-2" />
                                            </div>
                
                                            <!-- Duración -->
                                            <div class="mt-4">
                                                <x-input-label for="duracion" :value="__('Duración (horas)')" />
                                                <x-text-input id="duracion" class="block mt-1 w-full" type="number" name="duracion" :value="old('duracion')" />
                                                <x-input-error :messages="$errors->get('duracion')" class="mt-2" />
                                            </div>
                
                                            <!-- Nivel -->
                                            <div class="mt-4">
                                                <x-input-label for="nivel" :value="__('Nivel')" />
                                                <select id="nivel" name="nivel" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                    <option value="Principiante" @selected(old('nivel') == 'Principiante')>Principiante</option>
                                                    <option value="Intermedio" @selected(old('nivel') == 'Intermedio')>Intermedio</option>
                                                    <option value="Avanzado" @selected(old('nivel') == 'Avanzado')>Avanzado</option>
                                                </select>
                                                <x-input-error :messages="$errors->get('nivel')" class="mt-2" />
                                            </div>
                
                                            <div class="flex items-center justify-end mt-4">
                                                <a href="{{ route('cursos.index') }}" class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    {{ __('Cancelar') }}
                                                </a>
                                                <x-primary-button class="ms-4">
                                                    {{ __('Crear Curso') }}
                                                </x-primary-button>
                                            </div>
                                        </form>
                                    </div>            </div>
        </div>
    </div>
</x-app-layout>

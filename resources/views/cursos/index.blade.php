<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cursos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                                        <div class="p-6 text-gray-900">
                                                            @can('create', App\Models\Curso::class)
                                                            <div class="flex justify-end mb-4">
                                                                <a href="{{ route('cursos.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                                    {{ __('Crear Curso') }}
                                                                </a>
                                                            </div>
                                                            @endcan
                                                            <div class="overflow-x-auto bg-white rounded-lg shadow">                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Título
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Descripción
                                                        </th>
                                                        <th scope="col" class="relative px-6 py-3">
                                                            <span class="sr-only">Acciones</span>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @forelse ($cursos as $curso)
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="text-sm font-medium text-gray-900">{{ $curso->titulo }}</div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="text-sm text-gray-500">{{ Str::limit($curso->descripcion, 50) }}</div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                <a href="{{ route('cursos.show', $curso) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                                                <a href="{{ route('cursos.edit', $curso) }}" class="ms-2 text-yellow-600 hover:text-yellow-900">Editar</a>
                                                                <form action="{{ route('cursos.destroy', $curso) }}" method="POST" class="inline ms-2">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de que quieres eliminar este curso?')">Eliminar</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                                                No hay cursos para mostrar.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>            </div>
        </div>
    </div>
</x-app-layout>

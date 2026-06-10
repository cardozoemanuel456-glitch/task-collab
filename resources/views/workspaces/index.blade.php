<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Workspaces
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('workspaces.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded">Crear workspace</a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($workspaces->isEmpty())
                        <p>No hay workspaces todavía.</p>
                    @else
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="text-left">
                                    <th class="px-2 py-1">Nombre</th>
                                    <th class="px-2 py-1">Descripción</th>
                                    <th class="px-2 py-1">Código</th>
                                    <th class="px-2 py-1">Creador</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workspaces as $w)
                                    <tr class="border-t">
                                        <td class="px-2 py-2"><a href="{{ route('workspaces.show', $w) }}" class="text-blue-600">{{ $w->name }}</a></td>
                                        <td class="px-2 py-2">{{ $w->description }}</td>
                                        <td class="px-2 py-2">{{ $w->invite_code }}</td>
                                        <td class="px-2 py-2">{{ optional($w->creator)->name ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

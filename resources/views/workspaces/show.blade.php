<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $workspace->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p><strong>Descripción:</strong> {{ $workspace->description }}</p>
                    <p><strong>Código de invitación:</strong> {{ $workspace->invite_code }}</p>
                    <p><strong>Creador:</strong> {{ optional($workspace->creator)->name ?? '—' }}</p>

                    <hr class="my-4">

                    <h3 class="font-semibold">Boards</h3>
                    @if($workspace->boards->isEmpty())
                        <p>No hay boards aún.</p>
                    @else
                        <ul>
                            @foreach($workspace->boards as $board)
                                <li>{{ $board->name ?? 'Sin nombre' }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

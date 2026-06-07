<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Tablero TaskCollab') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
        <div class="bg-gray-800 p-4 rounded-lg shadow text-white flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <p class="text-sm font-semibold text-gray-400">Tu código de invitación para tus compañeros:</p>
                <p class="text-xl font-mono font-bold text-indigo-400">{{ auth()->id() }}</p>
            </div>
            
            <form action="{{ route('board.connect') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="number" name="team_id" placeholder="Pegar código de tu amigo" class="rounded bg-gray-900 border-gray-700 text-sm text-white px-3 py-2" value="{{ session('current_team_id') }}">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-xs font-bold px-4 py-2 rounded transition">
                    {{ session('current_team_id') ? 'Cambiar/Volver' : 'Conectarse' }}
                </button>
                @if(session('current_team_id'))
                    <a href="#" onclick="event.preventDefault(); document.querySelector('input[name=team_id]').value=''; this.closest('form').submit();" class="text-xs text-red-400 align-middle my-auto ml-2 hover:underline">Salir</a>
                @endif
            </form>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <form action="{{ route('tasks.store') }}" method="POST" class="flex gap-4">
                    @csrf
                    <input type="text" name="title" placeholder="¿Qué hay que hacer?" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                        + Añadir Tarjeta
                    </button>
                </form>
            </div>
            <livewire:task-board />
        </div>
    </div>
</x-app-layout>
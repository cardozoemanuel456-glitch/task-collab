@php
    // --- LÓGICA BACKEND DE LIVEWIRE ---
    // Cada 5 segundos, Livewire vuelve a ejecutar este bloque automático
    $currentTeamId = session('current_team_id', auth()->id());

    $todoTasks = \App\Models\Task::where('team_id', $currentTeamId)->where('status', 'todo')->get();
    $doneTasks = \App\Models\Task::where('team_id', $currentTeamId)->where('status', 'done')->get();
@endphp

<div wire:poll.5s class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
    <div class="bg-gray-100 p-4 rounded-lg shadow">
        <h3 class="font-bold text-lg text-gray-700 mb-4 uppercase tracking-wider">⏳ Por Hacer ({{ $todoTasks->count() }})</h3>
        <div class="space-y-3">
            @forelse($todoTasks as $task)
                <div x-data="{ openDelete: false }" class="bg-white p-4 rounded shadow border-l-4 border-amber-500 flex flex-col gap-2">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-800 font-medium">{{ $task->title }}</span>
                        <div class="flex items-center gap-2">
                            <form action="{{ route('tasks.update', $task) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded transition">
                                    Listo ✔
                                </button>
                            </form>
                            <button @click="openDelete = true" type="button" class="text-red-500 hover:text-red-700 text-xs font-semibold px-2 py-1 rounded bg-red-50 hover:bg-red-100 transition">
                                ❌ Borrar
                            </button>
                        </div>
                    </div>

                    <div x-show="openDelete" x-transition class="mt-2 p-2 bg-red-50 rounded border border-red-200 text-xs flex items-center justify-between">
                        <span class="text-red-700 font-medium">¿Confirmás eliminar?</span>
                        <div class="flex gap-2">
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-2 py-0.5 rounded font-bold hover:bg-red-700">Sí</button>
                            </form>
                            <button @click="openDelete = false" type="button" class="bg-gray-300 text-gray-700 px-2 py-0.5 rounded hover:bg-gray-400">No</button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 italic p-2">No hay tareas pendientes en este tablero.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-gray-100 p-4 rounded-lg shadow">
        <h3 class="font-bold text-lg text-gray-700 mb-4 uppercase tracking-wider">✅ Terminado ({{ $doneTasks->count() }})</h3>
        <div class="space-y-3">
            @forelse($doneTasks as $task)
                <div class="bg-white p-4 rounded shadow flex justify-between items-center border-l-4 border-green-500 mb-3">
                    <span class="text-gray-500 line-through">{{ $task->title }}</span>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('tasks.update', $task) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-xs bg-gray-200 hover:bg-amber-100 hover:text-amber-700 text-gray-600 font-bold py-1 px-2 rounded transition">
                                Regresar ↩
                            </button>
                        </form>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('¿Seguro querés eliminar esta tarjeta?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold px-2 py-1 border border-red-500/30 rounded bg-red-500/5 hover:bg-red-500/10 transition">
                                ❌ Borrar
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm italic p-2">Aún no terminaste ninguna tarea.</p>
            @endforelse
        </div>
    </div>

</div>
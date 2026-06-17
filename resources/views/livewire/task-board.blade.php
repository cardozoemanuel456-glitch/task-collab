<div class="flex h-screen bg-gray-900 text-gray-100 font-sans antialiased">

    <div class="w-64 bg-gray-800 border-r border-gray-700 flex flex-col justify-between p-4">
        <div>
            <div class="flex items-center space-x-2 mb-8 px-2">
                <div class="bg-indigo-600 p-2 rounded-lg text-white font-bold">TB</div>
                <span class="text-xl font-bold tracking-wider text-white">TaskPro</span>
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-center px-2 mb-3">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Mis Tableros</span>
                    <button wire:click="openCreateBoardModal"
                        class="text-indigo-400 hover:text-indigo-300 transition text-sm font-bold">+</button>
                </div>
                <nav class="space-y-1">
                    @foreach($tableros as $index => $tablero)
                                    <div class="group flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md
                                bg-gray-700/50 text-white hover:bg-gray-700 transition">
                                <span class="truncate">{{ $tablero }}</span>
                                <button wire:click="deleteBoard({{ $index }})"
                                    class="opacity-0 group-hover:opacity-100 text-red-400 hover:text-red-300 transition text-xs">
                                    Borrar
                                </button>
                        </div>
                    @endforeach
            </nav>
        </div>
    </div>

    <div class="bg-gray-700/40 p-3 rounded-lg border border-gray-700 text-center">
        <p class="text-xs text-gray-400 mb-1">Código de Invitación</p>
        <span class="font-mono text-sm font-bold text-indigo-400 tracking-wider">{{ $invitationCode }}</span>
    </div>
</div>

<div class="flex-1 flex flex-col overflow-hidden">
    <header class="bg-gray-800 border-b border-gray-700 h-16 flex items-center justify-between px-6">
        <h1 class="text-lg font-semibold text-white">Tablero de Trabajo</h1>
        <button wire:click="openModal"
            class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-md text-sm font-medium transition shadow-md">
            + Nueva Tarea
        </button>
    </header>

    <main class="flex-1 overflow-x-auto p-6 bg-gray-950 flex space-x-4">

        <div class="w-80 bg-gray-900 rounded-lg flex flex-col max-h-full border border-gray-800" x-on:dragover.prevent
            x-on:drop="$wire.moveTask(event.dataTransfer.getData('text/plain'), 'por_hacer')">
            <div class="p-3 font-semibold text-sm border-b border-gray-800 flex justify-between items-center">
                <span class="text-gray-300">Por Hacer</span>
                <span class="bg-gray-800 px-2 py-0.5 rounded text-xs text-gray-400">{{ $tasks->where('status', 'por_hacer')->count() }}</span>
            </div>
            <div class="p-3 overflow-y-auto space-y-3 flex-1 mini-kanban-zone">
                @foreach($tasks->where('status', 'por_hacer') as $task)
                    <div class="bg-gray-800 p-4 rounded-md border border-gray-700/60 shadow-sm flex flex-col justify-between group relative cursor-grab active:cursor-grabbing transition-all"
                        draggable="true"          x-on:dragstart="event.dataTransfer.setData('text/plain', {{ $task->id }})"
                        x-data="{ editing: false, editTitle: '{{ e($task->title) }}' }">
                        <div class="mb-3">
                            <span
                                class="inline-block text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded mb-2
                                        {{ $task->priority == 'Alta' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : '' }}
                                        {{ $task->priority == 'Media' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : '' }}
                                        {{ $task->priority == 'Baja' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : '' }}">
                                {{ $task->priority }}
                            </span>

                            <div class="mt-1">
                                <h4 x-show="!editing" class="text-sm font-medium text-white break-words leading-snug">{{ $task->title }}</h4>
                                <input x-show="editing" x-model="editTitle"
                                    x-on:keydown.enter="$wire.updateTaskTitle({{ $task->id }}, editTitle); editing = false"
                                    type="text"
                                    class="w-full bg-gray-900 border border-indigo-500 rounded p-1 text-sm text-white focus:outline-none">
                            </div>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-700/50 pt-3 mt-1">
                            <div class="flex space-x-2">
                                <button x-show="!editing" x-on:click="editing = true"
                                    class="text-xs text-indigo-400 hover:text-indigo-300 transition">Editar</button>
                                <button x-show="editing"
                                    x-on:click="$wire.updateTaskTitle({{ $task->id }}, editTitle); editing = false"
                                    class="text-xs text-emerald-400 hover:text-emerald-300 transition font-semibold">Guardar</button>
                                <button x-show="editing" x-on:click="editing = false; editTitle = '{{ e($task->title) }}'"
                                    class="text-xs text-gray-400 hover:text-gray-300 transition">X</button>

                                <button wire:click="deleteTask({{ $task->id }})"
                                    class="text-xs text-gray-500 hover:text-red-400 transition">Eliminar</button>
                            </div>
                                        <button wire:click="moveTask({{ $task->id }}, 'en_progreso')"
                                class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 flex items-center space-x-1 transition">
                                <span>Empezar</span>
                                <span>&rarr;</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="w-80 bg-gray-900 rounded-lg flex flex-col max-h-full border border-gray-800" x-on:dragover.prevent
            x-on:drop="$wire.moveTask(event.dataTransfer.getData('text/plain'), 'en_progreso')">
            <div class="p-3 font-semibold text-sm border-b border-gray-800 flex justify-between items-center">
                <span class="text-indigo-400">En Progreso</span>
                <span class="bg-gray-800 px-2 py-0.5 rounded text-xs text-indigo-300">{{ $tasks->where('status', 'en_progreso')->count() }}</span>
            </div>
            <div class="p-3 overflow-y-auto space-y-3 flex-1 mini-kanban-zone">
                    @foreach($tasks->where('status', 'en_progreso') as $task)
                                    <div class=" bg-gray-800 p-4 rounded-md border border-gray-700/60 shadow-sm flex flex-col
                            justify-between group relative cursor-grab active:cursor-grabbing transition-all" draggable="true"
                            x-on:dragstart="event.dataTransfer.setData('text/plain', {{ $task->id }})"
                            x-data="{ editing: false, editTitle: '{{ e($task->title) }}' }">
                            <div class="mb-3">
                                <span
                                    class="inline-block text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded mb-2
                                                {{ $task->priority == 'Alta' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : '' }}
                                                {{ $task->priority == 'Media' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : '' }}
                                                {{ $task->priority == 'Baja' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : '' }}">
                                    {{ $task->priority }}
                                </span>

                                <div class="mt-1">
                                    <h4 x-show="!editing" class="text-sm font-medium text-white break-words leading-snug">{{ $task->title }}</h4>
                                    <input x-show="editing" x-model="editTitle"
                                        x-on:keydown.enter="$wire.updateTaskTitle({{ $task->id }}, editTitle); editing = false"
                                        type="text"
                                        class="w-full bg-gray-900 border border-indigo-500 rounded p-1 text-sm text-white focus:outline-none">

                                </div>
                            </div>
                            <div class="flex items-center justify-between border-t border-gray-700/50 pt-3 mt-1">
                                <div class="flex space-x-2">
                                    <button x-show="!editing" x-on:click="editing = true"
                                        class="text-xs text-indigo-400 hover:text-indigo-300 transition">Editar</button>
                                    <button x-show="editing"
                                        x-on:click="$wire.updateTaskTitle({{ $task->id }}, editTitle); editing = false"
                                        class="text-xs text-emerald-400 hover:text-emerald-300 transition font-semibold">Guardar</button>
                                    <button x-show="editing" x-on:click="editing = false; editTitle = '{{ e($task->title) }}'"
                                        class="text-xs text-gray-400 hover:text-gray-300 transition">X</button>

                                    <button wire:click="deleteTask({{ $task->id }})" class="text-xs text-gray-500 hover:text-red-400
                                        transition">Eliminar</button>
                                </div>
                                <button wire:click="moveTask({{ $task->id }}, 'completado')"
                                    class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 flex items-center space-x-1 transition">
                                                    <span>Terminar</span>
                                    <span>&rarr;</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
        </div>
</div>

<div class="w-80 bg-gray-900 rounded-lg flex flex-col max-h-full border border-gray-800" x-on:dragover.prevent
    x-on:drop="$wire.moveTask(event.dataTransfer.getData('text/plain'), 'completado')">
    <div class="p-3 font-semibold text-sm border-b border-gray-800 flex justify-between items-center">
        <span class="text-emerald-400">Completado</span>
        <span class="bg-gray-800 px-2 py-0.5 rounded text-xs text-emerald-300">{{ $tasks->where('status', 'completado')->count() }}</span>
    </div>
    <div class="p-3 overflow-y-auto space-y-3 flex-1 mini-kanban-zone">
        @foreach($tasks->where('status', 'completado') as $task)
                        <div class="bg-gray-800 p-4 rounded-md border border-gray-700/60 shadow-sm flex flex-col justify-between
                    group relative cursor-grab active:cursor-grabbing transition-all"
                    draggable="true"
                    x-on:dragstart="event.dataTransfer.setData('text/plain', {{ $task->id }})"
                    x-data="{ editing: false, editTitle: '{{ e($task->title) }}' }">
                    <div class="mb-3">
                        <span
                            class="inline-block text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded mb-2
                                                {{ $task->priority == 'Alta' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : '' }}
                                                {{ $task->priority == 'Media' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : '' }}
                                                {{ $task->priority == 'Baja' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : '' }}">
                            {{ $task->priority }}
                        </span>

                        <div class="mt-1">
                            <h4 x-show="!editing" class="text-sm font-medium text-white break-words leading-snug">{{ $task->title }}
                            </h4>
                            <input x-show="editing" x-model="editTitle"
                                x-on:keydown.enter="$wire.updateTaskTitle({{ $task->id }}, editTitle); editing = false" type="text"
                                class="w-full bg-gray-900 border border-indigo-500 rounded p-1 text-sm text-white focus:outline-none">
                        </div>
                    </div>
                        <div class="flex items-center justify-between border-t border-gray-700/50 pt-3 mt-1">
                    <div class="flex space-x-2">
                        <button x-show="!editing" x-on:click="editing = true"
                            class="text-xs text-indigo-400 hover:text-indigo-300 transition">Editar</button>
                        <button x-show="editing" x-on:click="$wire.updateTaskTitle({{ $task->id }}, editTitle); editing = false"
                            class="text-xs text-emerald-400 hover:text-emerald-300 transition font-semibold">Guardar</button>
                        <button x-show="editing" x-on:click="editing = false; editTitle = '{{ e($task->title) }}'"
                            class="text-xs text-gray-400 hover:text-gray-300 transition">X</button>

                        <button wire:click="deleteTask({{ $task->id }})"
                            class="text-xs text-gray-500 hover:text-red-400 transition">Eliminar</button>
                    </div>
                </div>
            </div>
        @endforeach
</div>
</div>

</main>
</div>

@if($showModal)
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div class="bg-gray-800 border border-gray-700 rounded-lg max-w-md w-full p-6 shadow-xl">
            <h3 class="text-lg font-bold text-white mb-4">Añadir Nueva Tarea</h3>
            <form wire:submit.prevent="addTask" class="space-y-4">
                       <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Título de la
                        tarea</label>
                                        <input type="text" wire:model="newTaskTitle"
                        class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-white focus:outline-none focus:border-indigo-500">
                    @error('newTaskTitle') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Prioridad</label>
                    <select wire:model="newTaskPriority"
                        class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-white focus:outline-none focus:border-indigo-500">
                        <option value="Baja">Baja</option>
                        <option value="Media">Media</option>
                        <option value="Alta">Alta</option>
                    </select>
                    </div> <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" wire:click="closeModal"
                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded text-sm font-medium transition">Cancelar</button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded text-sm font-medium text-white transition shadow-md">Guardar
                        Tarea</button>
                </div>
            </form>
        </div>
    </div>
@endif

@if($showBoardModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="bg-gray-800 border border-gray-700 rounded-lg max-w-md w-full p-6 shadow-xl">
        <h3 class="text-lg font-bold text-white mb-4">Crear Nuevo Tablero</h3>
        <form wire:submit.prevent="addBoard" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Nombre del
                    Tablero</label>
                <input type="text" wire:model="newBoardName"
                    class="w-full bg-gray-900 border border-gray-700 rounded p-2 text-white focus:outline-none focus:border-indigo-500">
                @error('newBoardName') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" wire:click="closeBoardModal"
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded text-sm font-medium transition">Cancelar</button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded text-sm font-medium text-white transition shadow-md">Crear</button>
            </div>
        </form>
    </div>
    </div>
@endif

</div>
@extends('paginas.app')

@section('title', 'TaskCollab - ' . ($pagina->titulo ?? 'Tablero'))

@section('breadcrumb', 'Tablero / ' . ($pagina->titulo ?? ''))

@section('header-actions')
    <button onclick="alert('Módulo de invitación en desarrollo')"
            class="bg-teal-600 hover:bg-teal-500 text-white text-xs font-semibold px-3 py-1.5 rounded-xl transition cursor-pointer">
        👤 Invitar
    </button>
@endsection

@section('content')

@php
    $isDark    = auth()->user()->dark_mode;
    $bgCard    = $isDark ? 'bg-[#1e1e1e] border-neutral-800 shadow-2xl' : 'bg-white border-neutral-200 shadow-md';
    $bgInput   = $isDark ? 'bg-[#121212] border-neutral-800 text-neutral-200' : 'bg-neutral-100 border-neutral-300 text-neutral-800';
    $textTitle = $isDark ? 'text-neutral-100' : 'text-neutral-900';
    $textMuted = $isDark ? 'text-neutral-500' : 'text-neutral-400';
@endphp

    <div class="flex items-center space-x-3 mb-8">
        <span class="text-4xl">{{ $pagina->icono ?? '📋' }}</span>
        <h1 class="text-3xl md:text-4xl font-bold {{ $textTitle }}">{{ $pagina->titulo }}</h1>

        <form action="{{ route('paginas.destroy', $pagina->id) }}" method="POST"
              onsubmit="return confirm('¿Seguro querés eliminar este tablero completo?')"
              class="ml-auto">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="text-xs p-1.5 rounded-lg border cursor-pointer transition
                           {{ $isDark ? 'text-neutral-500 border-neutral-800 hover:text-red-400 hover:border-red-900' : 'text-neutral-400 border-neutral-300 hover:text-red-600 hover:border-red-200' }}">
                🗑️ Eliminar Tablero
            </button>
        </form>
    </div>

    <div class="{{ $bgCard }} rounded-2xl p-4 md:p-6 border">

        <div class="flex flex-col md:flex-row gap-4 justify-between items-center mb-6">
            <div class="relative w-full md:w-64">
                <input type="text" placeholder="Buscar tareas (Filtro)..." disabled
                       class="w-full pl-3 pr-4 py-1.5 {{ $bgInput }} border rounded-xl text-xs {{ $textMuted }} cursor-not-allowed">
            </div>

            <button onclick="openTaskModal()" type="button"
                    class="w-full md:w-auto bg-teal-600 hover:bg-teal-500 text-white font-semibold px-4 py-1.5 rounded-xl text-sm transition shrink-0 cursor-pointer text-center">
                + Añadir Tarea
            </button>
        </div>

        <div class="space-y-2">
            @forelse($pagina->tareas as $tarea)
                <div class="flex items-center justify-between p-3 {{ $bgInput }} border rounded-xl hover:border-teal-600/40 transition relative">
                    
                    <div id="task-desc-{{ $tarea->id }}" class="hidden">{{ $tarea->description }}</div>

                    <div class="flex items-center space-x-3 min-w-0 flex-1">
                        <form action="{{ route('tareas.update', $tarea->id) }}" method="POST" class="flex items-center">
                            @csrf
                            @method('PATCH')
                            <input type="checkbox"
                                   onchange="this.form.submit()"
                                   {{ $tarea->status === 'done' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded text-teal-600 focus:ring-0 cursor-pointer">
                        </form>

                        <span class="text-sm truncate {{ $tarea->status === 'done' ? 'line-through opacity-45' : '' }}">
                            {{ $tarea->title }}
                        </span>
                    </div>

                    <div class="relative inline-block text-left ml-2">
                        <button onclick="toggleTaskDropdown('{{ $tarea->id }}', event)" 
                                class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-200 p-1 rounded-lg transition cursor-pointer flex items-center justify-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                            </svg>
                        </button>
                        
                        <div id="dropdown-task-{{ $tarea->id }}" 
                             class="hidden absolute right-0 mt-1 w-32 rounded-xl shadow-lg py-1 border z-30 {{ $isDark ? 'bg-[#1e1e1e] border-neutral-800 text-neutral-300' : 'bg-white border-neutral-200 text-neutral-700' }}">
                            
                            <button type="button" 
                                    onclick="openEditTaskModal(this)"
                                    data-id="{{ $tarea->id }}"
                                    data-title="{{ $tarea->title }}"
                                    data-priority="{{ $tarea->priority ?? 'media' }}"
                                    data-assigned_to="{{ $tarea->assigned_to ?? '' }}"
                                    data-start_date="{{ $tarea->start_date ? \Carbon\Carbon::parse($tarea->start_date)->format('Y-m-d') : '' }}"
                                    data-end_date="{{ $tarea->end_date ? \Carbon\Carbon::parse($tarea->end_date)->format('Y-m-d') : '' }}"
                                    class="w-full text-left block px-4 py-2 text-xs hover:bg-teal-600 hover:text-white transition cursor-pointer font-medium">
                                ✏️ Editar
                            </button>
                            
                            <hr class="{{ $isDark ? 'border-neutral-800' : 'border-neutral-200' }}">
                            
                            <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" onsubmit="return confirm('¿Seguro querés eliminar esta tarea?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full text-left block px-4 py-2 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-neutral-800 transition cursor-pointer font-medium">
                                    🗑️ Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 {{ $textMuted }} text-sm italic">
                    🎉 ¡No hay tareas pendientes en este tablero!
                </div>
            @endforelse
        </div>

    </div>

    <div id="taskModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 transition-opacity duration-200">
        <div class="absolute inset-0 bg-black/60 backend-blur-sm" onclick="closeTaskModal()"></div>
        
        <div class="{{ $bgCard }} w-full max-w-lg rounded-2xl border p-6 relative z-10 shadow-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modalTitle" class="text-lg font-bold {{ $textTitle }}">📋 Crear Nueva Tarea</h3>
                <button onclick="closeTaskModal()" class="text-neutral-400 hover:text-red-500 text-xl cursor-pointer">✕</button>
            </div>

            <form id="taskForm" action="{{ route('tareas.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="pagina_id" value="{{ $pagina->id }}">

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-1 {{ $textMuted }}">Nombre de la tarea</label>
                    <input type="text" name="title" id="input_title" required placeholder="Ej: Resolver la guía de ejercicios"
                           class="w-full {{ $bgInput }} border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-teal-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-1 {{ $textMuted }}">Descripción</label>
                    <textarea name="description" id="input_description" rows="3" placeholder="Detalles adicionales..."
                              class="w-full {{ $bgInput }} border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-teal-600 resize-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider mb-1 {{ $textMuted }}">Prioridad</label>
                        <select name="priority" id="input_priority" class="w-full {{ $bgInput }} border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-teal-600 cursor-pointer">
                            <option value="baja">🟢 Baja</option>
                            <option value="media" selected>🟡 Media</option>
                            <option value="alta">🔴 Alta</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider mb-1 {{ $textMuted }}">Asignar a</label>
                        @if(isset($pagina->usuarios) && $pagina->usuarios->count() > 0)
                            <select name="assigned_to" id="input_assigned_to" class="w-full {{ $bgInput }} border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-teal-600 cursor-pointer">
                                <option value="">Sin asignar (Libre)</option>
                                @foreach($pagina->usuarios as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <select disabled class="w-full {{ $bgInput }} border rounded-xl px-3 py-2 text-sm opacity-50 cursor-not-allowed">
                                <option value="">Tablero personal</option>
                            </select>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider mb-1 {{ $textMuted }}">Fecha de Inicio</label>
                        <input type="date" name="start_date" id="input_start_date"
                               class="w-full {{ $bgInput }} border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-teal-600 dark:[color-scheme:dark]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider mb-1 {{ $textMuted }}">Fecha de Fin</label>
                        <input type="date" name="end_date" id="input_end_date"
                               class="w-full {{ $bgInput }} border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-teal-600 dark:[color-scheme:dark]">
                    </div>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" onclick="closeTaskModal()"
                            class="px-4 py-2 rounded-xl text-sm font-medium border cursor-pointer {{ $isDark ? 'border-neutral-700 hover:bg-neutral-800 text-neutral-300' : 'border-neutral-300 hover:bg-neutral-100 text-neutral-700' }}">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="bg-teal-600 hover:bg-teal-500 text-white font-semibold px-5 py-2 rounded-xl text-sm transition cursor-pointer shadow-md">
                        Guardar Tarea
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function toggleTaskDropdown(taskId, event) {
            event.stopPropagation();
            document.querySelectorAll('[id^="dropdown-task-"]').forEach(el => {
                if (el.id !== `dropdown-task-${taskId}`) el.classList.add('hidden');
            });
            document.getElementById(`dropdown-task-${taskId}`).classList.toggle('hidden');
        }

        function openTaskModal() {
            document.getElementById('modalTitle').innerText = '📋 Crear Nueva Tarea';
            document.getElementById('formMethod').value = 'POST';
            
            const form = document.getElementById('taskForm');
            form.action = "{{ route('tareas.store') }}";
            form.reset();

            const userSelect = document.getElementById('input_assigned_to');
            if (userSelect) userSelect.value = '';

            const modal = document.getElementById('taskModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function openEditTaskModal(button) {
            const taskId = button.dataset.id;

            document.getElementById('modalTitle').innerText = '✏️ Editar Tarea';
            document.getElementById('formMethod').value = 'PATCH'; 
            
            const form = document.getElementById('taskForm');
            const baseRoute = "{{ route('tareas.update', 'TASK_ID') }}";
            form.action = baseRoute.replace('TASK_ID', taskId);
            
            document.getElementById('input_title').value = button.dataset.title || '';
            
            const hiddenDescContainer = document.getElementById(`task-desc-${taskId}`);
            document.getElementById('input_description').value = hiddenDescContainer ? hiddenDescContainer.textContent.trim() : '';
            
            document.getElementById('input_priority').value = button.dataset.priority || 'media';
            
            const userSelect = document.getElementById('input_assigned_to');
            if (userSelect && !userSelect.disabled) {
                userSelect.value = button.dataset.assigned_to || '';
            }
            
            document.getElementById('input_start_date').value = button.dataset.start_date || '';
            document.getElementById('input_end_date').value = button.dataset.end_date || '';
            
            document.querySelectorAll('[id^="dropdown-task-"]').forEach(el => el.classList.add('hidden'));

            const modal = document.getElementById('taskModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeTaskModal() {
            const modal = document.getElementById('taskModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        window.addEventListener('click', function (e) {
            if (!e.target.closest('[id^="dropdown-task-"]')) {
                document.querySelectorAll('[id^="dropdown-task-"]').forEach(el => el.classList.add('hidden'));
            }
        });
    </script>
@endpush
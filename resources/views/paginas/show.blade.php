@extends('paginas.app')

@section('title', 'TaskCollab - ' . ($pagina->titulo ?? 'Tablero'))

@section('breadcrumb', 'Espacio / ' . ($pagina->titulo ?? ''))

@section('header-actions')
    <!-- Botón "Invitar" actualizado -->
    <button onclick="document.getElementById('modalInvitar').classList.remove('hidden')"
        class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Invitar
    </button>

    <!-- Ventana Modal (Oculta por defecto) -->
    <div id="modalInvitar" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full relative">
            <button onclick="document.getElementById('modalInvitar').classList.add('hidden')"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">✕</button>

            <!-- Aquí se cargará el componente Livewire -->
            <livewire:invitar-pagina :pagina-id="$pagina->id" />
        </div>
    </div>
@endsection


@section('content')

@php
    $isDark    = auth()->user()->dark_mode;
    // Variables de estilo unificadas con app.blade.php
    $bgCard    = $isDark ? 'bg-[#121214] border-zinc-800/70 shadow-xl'        : 'bg-white border-slate-200/80 shadow-sm';
    $bgInput   = $isDark ? 'bg-[#18181b] border-zinc-800 text-zinc-100'       : 'bg-slate-50 border-slate-200 text-slate-900';
    $textTitle = $isDark ? 'text-zinc-50'                                     : 'text-slate-900';
    $textMuted = $isDark ? 'text-zinc-500'                                    : 'text-slate-400';
    $borderMuted = $isDark ? 'border-zinc-800/60'                             : 'border-slate-200/80';
@endphp

    <div class="flex items-center space-x-3 mb-6">
        <div class="bg-indigo-600/10 text-indigo-500 p-2 rounded-xl border {{ $isDark ? 'border-indigo-500/20' : 'border-indigo-200' }}">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9
                 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold tracking-tight {{ $textTitle }}">{{ $pagina->titulo ?? 'Sin título' }}</h1>
    </div>

    <div class="mb-8">
        <div class="w-full md:max-w-md flex gap-2">
            <input type="text" id="newTaskTitleInput" placeholder="Añadir nueva tarea..."
                   class="w-full {{ $bgInput }} border rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 
                   focus:ring-indigo-500/30 transition-all shadow-sm"
                   onkeydown="if(event.key === 'Enter') openCreateTaskModal()">
            <button type="button" onclick="openCreateTaskModal()"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 rounded-xl font-bold text-sm cursor-pointer transition-colors shadow-sm whitespace-nowrap">Añadir</button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">

        <div class="{{ $bgCard }} rounded-2xl border p-4">
            <div class="flex items-center justify-between mb-4 pb-2 border-b {{ $borderMuted }}">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-zinc-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-base font-bold {{ $textTitle }}">Por hacer</h2>
                </div>
                <span id="count-todo" class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $isDark ? 'bg-zinc-800 text-zinc-400' : 'bg-slate-100 text-slate-500' }}">
                    {{ $pagina->tareas->where('status', 'todo')->count() }}
                </span>
            </div>

            <div id="column-todo" ondragover="allowDrop(event)" ondragenter="dragEnter(event, 'todo')" ondragleave="dragLeave(event, 'todo')" ondrop="drop(event, 'todo')" class="space-y-2.5 min-h-[200px] transition-all p-1 rounded-xl">
                @foreach($pagina->tareas->where('status', 'todo') as $tarea)
                    <div id="task-{{ $tarea->id }}" draggable="true" ondragstart="drag(event)" class="task-card {{ $isDark ? 'bg-[#18181b] border-zinc-800/80 hover:border-zinc-700' : 'bg-slate-50 border-slate-200 hover:border-slate-300' }} cursor-grab active:cursor-grabbing border p-3 rounded-xl flex items-center justify-between transition-all shadow-xs group">
                        <div class="flex items-center space-x-3 min-w-0 flex-1">
                            @php
                                $priorityColor = match($tarea->priority ?? 'media') {
                                    'alta' => 'bg-red-500 shadow-red-500/50',
                                    'media' => 'bg-amber-500 shadow-amber-500/50',
                                    'baja' => 'bg-emerald-500 shadow-emerald-500/50',
                                    default => 'bg-slate-400'
                                };
                            @endphp
                            <form action="{{ route('tareas.update', $tarea->id) }}" method="POST" class="flex items-center m-0 p-0">
                                @csrf
                                @method('PATCH')
                                <label class="relative flex items-center cursor-pointer group/cb">
                                    <input type="checkbox" onchange="this.form.submit()" {{ $tarea->status === 'done' ? 'checked' : '' }} class="peer sr-only">
                                    <div class="w-4 h-4 rounded border {{ $isDark ? 'border-zinc-600 bg-[#121214] peer-checked:bg-indigo-500 peer-checked:border-indigo-500' : 'border-slate-300 bg-white peer-checked:bg-indigo-600 peer-checked:border-indigo-600' }} flex items-center justify-center transition-all shadow-sm group-hover/cb:border-indigo-400">
                                        <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </label>
                            </form>
                            <div class="w-2 h-2 rounded-full {{ $priorityColor }} shadow-sm shrink-0" title="Prioridad: {{ ucfirst($tarea->priority ?? 'media') }}"></div>
                            <span class="task-title text-sm font-medium truncate {{ $textTitle }} {{ $tarea->status === 'done' ? 'line-through opacity-45' : '' }}">
                                {{ $tarea->title }}
                            </span>
                        </div>
                        
                        <div class="flex items-center space-x-1 md:opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="openTaskModal(this)"
                                    data-id="{{ $tarea->id }}"
                                    data-title="{{ $tarea->title }}"
                                    data-status="{{ $tarea->status }}"
                                    data-priority="{{ $tarea->priority }}"
                                    data-assigned_to="{{ $tarea->assigned_to }}"
                                    data-start_date="{{ $tarea->start_date }}"
                                    data-end_date="{{ $tarea->end_date }}"
                                    class="p-1 text-slate-400 hover:text-indigo-500 rounded transition-colors cursor-pointer" title="Editar detalles">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 
                                    21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                            <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" class="m-0 p-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-slate-400 hover:text-red-500 rounded transition-colors cursor-pointer" title="Eliminar">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 
                                        7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div id="task-desc-{{ $tarea->id }}" class="hidden">{{ $tarea->description }}</div>
                @endforeach
                
                <div id="placeholder-todo" class="text-center py-6 {{ $textMuted }} text-xs italic {{ $pagina->tareas->where('status', 'todo')->count() > 0 ? 'hidden' : '' }}">
                    No hay tareas pendientes
                </div>
            </div>
        </div>

        <div class="{{ $bgCard }} rounded-2xl border p-4">
            <div class="flex items-center justify-between mb-4 pb-2 border-b {{ $borderMuted }}">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <h2 class="text-base font-bold {{ $textTitle }}">En proceso</h2>
                </div>
                <span id="count-doing" class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $isDark ? 'bg-zinc-800 text-zinc-400' : 'bg-slate-100 text-slate-500' }}">
                    {{ $pagina->tareas->where('status', 'doing')->count() }}
                </span>
            </div>

            <div id="column-doing" ondragover="allowDrop(event)" ondragenter="dragEnter(event, 'doing')" ondragleave="dragLeave(event, 'doing')" ondrop="drop(event, 'doing')" class="space-y-2.5 min-h-[200px] transition-all p-1 rounded-xl">
                @foreach($pagina->tareas->where('status', 'doing') as $tarea)
                    <div id="task-{{ $tarea->id }}" draggable="true" ondragstart="drag(event)" class="task-card {{ $isDark ? 'bg-[#18181b] border-zinc-800/80 hover:border-zinc-700' : 'bg-slate-50 border-slate-200 hover:border-slate-300' }} cursor-grab active:cursor-grabbing border p-3 rounded-xl flex items-center justify-between transition-all shadow-xs group">
                        <div class="flex items-center space-x-3 min-w-0 flex-1">
                            @php
                                $priorityColor = match($tarea->priority ?? 'media') {
                                    'alta' => 'bg-red-500 shadow-red-500/50',
                                    'media' => 'bg-amber-500 shadow-amber-500/50',
                                    'baja' => 'bg-emerald-500 shadow-emerald-500/50',
                                    default => 'bg-slate-400'
                                };
                            @endphp
                            <form action="{{ route('tareas.update', $tarea->id) }}" method="POST" class="flex items-center m-0 p-0 mr-1">
                                @csrf
                                @method('PATCH')
                                <label class="relative flex items-center justify-center cursor-pointer group/cb">
                                    <input type="checkbox" onchange="this.form.submit()" {{ $tarea->status === 'done' ? 'checked' : '' }} class="peer sr-only">
                                    <div class="w-4 h-4 rounded border {{ $isDark ? 'border-zinc-600 bg-[#121214] peer-checked:bg-indigo-500 peer-checked:border-indigo-500' : 'border-slate-300 bg-white peer-checked:bg-indigo-600 peer-checked:border-indigo-600' }} transition-all shadow-sm group-hover/cb:border-indigo-400"></div>
                                    <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity absolute pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </label>
                            </form>
                            <div class="w-2 h-2 rounded-full {{ $priorityColor }} shadow-sm shrink-0 mr-1" title="Prioridad: {{ ucfirst($tarea->priority ?? 'media') }}"></div>
                            <span class="task-title text-sm font-medium truncate {{ $textTitle }} {{ $tarea->status === 'done' ? 'line-through opacity-45' : '' }}">
                                {{ $tarea->title }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-1 md:opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="openTaskModal(this)"
                                    data-id="{{ $tarea->id }}" data-title="{{ $tarea->title }}" data-status="{{ $tarea->status }}"
                                    data-priority="{{ $tarea->priority }}" data-assigned_to="{{ $tarea->assigned_to }}"
                                    data-start_date="{{ $tarea->start_date }}" data-end_date="{{ $tarea->end_date }}"
                                    class="p-1 text-slate-400 hover:text-indigo-500 rounded transition-colors cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 
                                    21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                            <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" class="m-0 p-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-slate-400 hover:text-red-500 rounded transition-colors cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 
                                        7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div id="task-desc-{{ $tarea->id }}" class="hidden">{{ $tarea->description }}</div>
                @endforeach
                
                <div id="placeholder-doing" class="text-center py-6 {{ $textMuted }} text-xs italic {{ $pagina->tareas->where('status', 'doing')->count() > 0 ? 'hidden' : '' }}">
                    Ninguna tarea en curso
                </div>
            </div>
        </div>

        <div class="{{ $bgCard }} rounded-2xl border p-4">
            <div class="flex items-center justify-between mb-4 pb-2 border-b {{ $borderMuted }}">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-base font-bold {{ $textTitle }}">Terminado</h2>
                </div>
                <span id="count-done" class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $isDark ? 'bg-zinc-800 text-zinc-400' : 'bg-slate-100 text-slate-500' }}">
                    {{ $pagina->tareas->where('status', 'done')->count() }}
                </span>
            </div>

            <div id="column-done" ondragover="allowDrop(event)" ondragenter="dragEnter(event, 'done')" ondragleave="dragLeave(event, 'done')" ondrop="drop(event, 'done')" class="space-y-2.5 min-h-[200px] transition-all p-1 rounded-xl">
                @foreach($pagina->tareas->where('status', 'done') as $tarea)
                    <div id="task-{{ $tarea->id }}" draggable="true" ondragstart="drag(event)" class="task-card {{ $isDark ? 'bg-[#18181b]/60 border-zinc-900' : 'bg-slate-50/60 border-slate-200' }} cursor-grab active:cursor-grabbing border p-3 rounded-xl flex items-center justify-between transition-all group">
                        <div class="flex items-center space-x-3 min-w-0 flex-1">
                            @php
                                $priorityColor = match($tarea->priority ?? 'media') {
                                    'alta' => 'bg-red-500 shadow-red-500/50',
                                    'media' => 'bg-amber-500 shadow-amber-500/50',
                                    'baja' => 'bg-emerald-500 shadow-emerald-500/50',
                                    default => 'bg-slate-400'
                                };
                            @endphp
                            <form action="{{ route('tareas.update', $tarea->id) }}" method="POST" class="flex items-center m-0 p-0">
                                @csrf
                                @method('PATCH')
                                <label class="relative flex items-center cursor-pointer group/cb">
                                    <input type="checkbox" onchange="this.form.submit()" {{ $tarea->status === 'done' ? 'checked' : '' }} class="peer sr-only">
                                    <div class="w-4 h-4 rounded border {{ $isDark ? 'border-zinc-600 bg-[#121214] peer-checked:bg-indigo-500 peer-checked:border-indigo-500' : 'border-slate-300 bg-white peer-checked:bg-indigo-600 peer-checked:border-indigo-600' }} flex items-center justify-center transition-all shadow-sm group-hover/cb:border-indigo-400">
                                        <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </label>
                            </form>
                            <div class="w-2 h-2 rounded-full {{ $priorityColor }} shadow-sm shrink-0" title="Prioridad: {{ ucfirst($tarea->priority ?? 'media') }}"></div>
                            <span class="task-title text-sm font-medium truncate line-through opacity-45 {{ $textTitle }}">
                                {{ $tarea->title }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-1 md:opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="openTaskModal(this)"
                                    data-id="{{ $tarea->id }}" data-title="{{ $tarea->title }}" data-status="{{ $tarea->status }}"
                                    data-priority="{{ $tarea->priority }}" data-assigned_to="{{ $tarea->assigned_to }}"
                                    data-start_date="{{ $tarea->start_date }}" data-end_date="{{ $tarea->end_date }}"
                                    class="p-1 text-slate-400 hover:text-indigo-500 rounded transition-colors cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 
                                    21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                            <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" class="m-0 p-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-slate-400 hover:text-red-500 rounded transition-colors cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5
                                         7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div id="task-desc-{{ $tarea->id }}" class="hidden">{{ $tarea->description }}</div>
                @endforeach
                
                <div id="placeholder-done" class="text-center py-6 {{ $textMuted }} text-xs italic {{ $pagina->tareas->where('status', 'done')->count() > 0 ? 'hidden' : '' }}">
                    No hay tareas completadas
                </div>
            </div>
        </div>

    </div>

    <div id="taskModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-xs p-4">
        <div class="{{ $isDark ? 'bg-[#121214] border-zinc-800' : 'bg-white border-slate-200' }} border w-full max-w-md rounded-2xl shadow-2xl overflow-hidden 
        transform transition-all animate-in fade-in zoom-in-95 duration-150">
            
            <div class="p-4 border-b {{ $borderMuted }} flex justify-between items-center">
                <h3 class="text-base font-bold {{ $textTitle }} flex items-center space-x-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M11 3h5a2 2 0 012 2v5m-9
                         7h3l12-12a1.5 1.5 0 00-2-2l-12 12v3z" />
                    </svg>
                    <span id="modalTitleText">Editar Detalles de Tarea</span>
                </h3>
                <button onclick="closeTaskModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-zinc-200 text-sm cursor-pointer p-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="editTaskForm" method="POST" class="p-5 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="form_method" value="PATCH">
                <input type="hidden" name="pagina_id" id="input_pagina_id" value="{{ $pagina->id }}">

                <div>
                    <label class="block text-xs font-semibold text-slate-400 dark:text-zinc-400 uppercase tracking-wider mb-1.5">Título de la Tarea</label>
                    <input type="text" id="input_title" name="title" required
                           class="w-full {{ $bgInput }} border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1
                            focus:ring-indigo-500/30 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 dark:text-zinc-400 uppercase tracking-wider mb-1.5">Estado actual</label>
                    <select id="input_status" name="status"
                            class="w-full {{ $bgInput }} border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1
                             focus:ring-indigo-500/30 transition-all cursor-pointer">
                        <option value="todo">Por hacer</option>
                        <option value="doing">En proceso</option>
                        <option value="done">Terminado</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 dark:text-zinc-400 uppercase tracking-wider mb-1.5">Descripción o Notas</label>
                    <textarea id="input_description" name="description" rows="3" placeholder="Añadir una descripción detallada..."
                              class="w-full {{ $bgInput }} border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1
                               focus:ring-indigo-500/30 transition-all resize-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 dark:text-zinc-400 uppercase tracking-wider mb-1.5">Prioridad</label>
                        <select id="input_priority" name="priority"
                                class="w-full {{ $bgInput }} border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 
                                focus:ring-indigo-500/30 transition-all cursor-pointer">
                            <option value="baja">Baja</option>
                            <option value="media">Media</option>
                            <option value="alta">Alta</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 dark:text-zinc-400 uppercase tracking-wider mb-1.5">Responsable</label>
                        <select id="input_assigned_to" name="assigned_to"
                                class="w-full {{ $bgInput }} border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 
                                focus:ring-indigo-500/30 transition-all cursor-pointer"
                                {{ $pagina->user_id !== auth()->id() ? 'disabled' : '' }}>
                            <option value="">Sin asignar</option>
                            <option value="{{ $pagina->creator->id }}">{{ $pagina->creator->name }} (Creador)</option>
                            @foreach($pagina->miembros as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 dark:text-zinc-400 uppercase tracking-wider mb-1.5">Fecha Inicio</label>
                        <input type="date" id="input_start_date" name="start_date"
                               class="w-full {{ $bgInput }} border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 
                               focus:ring-indigo-500/30 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 dark:text-zinc-400 uppercase tracking-wider mb-1.5">Fecha Entrega</label>
                        <input type="date" id="input_end_date" name="end_date"
                               class="w-full {{ $bgInput }} border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 
                               focus:ring-indigo-500/30 transition-all">
                    </div>
                </div>

                <div class="pt-2 flex justify-end space-x-2.5">
                    <button type="button" onclick="closeTaskModal()"
                            class="px-4 py-2 rounded-xl border {{ $isDark ? 'border-zinc-800 text-zinc-300 hover:bg-zinc-800' : 'border-slate-200 
                            text-slate-700 hover:bg-slate-100' }} text-xs font-semibold transition-colors cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" id="modalSubmitBtn"
                            class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-xl text-xs font-bold transition-colors cursor-pointer 
                            shadow-sm shadow-indigo-600/10">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function openCreateTaskModal() {
            const titleInput = document.getElementById('newTaskTitleInput').value;
            
            const form = document.getElementById('editTaskForm');
            form.action = `/tareas`;
            document.getElementById('form_method').value = 'POST';
            
            document.getElementById('modalTitleText').textContent = 'Crear Nueva Tarea';
            document.getElementById('modalSubmitBtn').textContent = 'Crear Tarea';
            
            document.getElementById('input_title').value = titleInput || '';
            document.getElementById('input_status').value = 'todo';
            document.getElementById('input_description').value = '';
            document.getElementById('input_priority').value = 'media';
            
            const userSelect = document.getElementById('input_assigned_to');
            if (userSelect && !userSelect.disabled) {
                userSelect.value = '';
            }
            
            document.getElementById('input_start_date').value = '';
            document.getElementById('input_end_date').value = '';

            const modal = document.getElementById('taskModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function openTaskModal(button) {
            const taskId = button.dataset.id;
            
            const form = document.getElementById('editTaskForm');
            form.action = `/tareas/${taskId}`;
            document.getElementById('form_method').value = 'PATCH';
            
            document.getElementById('modalTitleText').textContent = 'Editar Detalles de Tarea';
            document.getElementById('modalSubmitBtn').textContent = 'Guardar Cambios';
            
            document.getElementById('input_title').value = button.dataset.title;
            document.getElementById('input_status').value = button.dataset.status;
            
            const hiddenDescContainer = document.getElementById(`task-desc-${taskId}`);
            document.getElementById('input_description').value = hiddenDescContainer ? hiddenDescContainer.textContent.trim() : '';
            
            document.getElementById('input_priority').value = button.dataset.priority || 'media';
            
            const userSelect = document.getElementById('input_assigned_to');
            if (userSelect && !userSelect.disabled) {
                userSelect.value = button.dataset.assigned_to || '';
            }
            
            document.getElementById('input_start_date').value = button.dataset.start_date || '';
            document.getElementById('input_end_date').value = button.dataset.end_date || '';

            const modal = document.getElementById('taskModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeTaskModal() {
            const modal = document.getElementById('taskModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // ── Drag & Drop ─────────────────────────────────────────
        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drag(ev) {
            ev.dataTransfer.setData("text/plain", ev.target.id);
        }

        function dragEnter(ev, status) {
            ev.preventDefault();
            const col = document.getElementById(`column-${status}`);
            col.classList.add('bg-indigo-500/5', 'ring-2', 'ring-dashed', 'ring-indigo-500/20');
        }

        function dragLeave(ev, status) {
            const col = document.getElementById(`column-${status}`);
            col.classList.remove('bg-indigo-500/5', 'ring-2', 'ring-dashed', 'ring-indigo-500/20');
        }

        function drop(ev, status) {
            ev.preventDefault();
            const col = document.getElementById(`column-${status}`);
            col.classList.remove('bg-indigo-500/5', 'ring-2', 'ring-dashed', 'ring-indigo-500/20');
            
            const data = ev.dataTransfer.getData("text/plain");
            const taskElement = document.getElementById(data);
            if (!taskElement) return;
            
            const taskId = data.replace('task-', '');
            
            // Append visually before the placeholder
            const placeholder = document.getElementById(`placeholder-${status}`);
            col.insertBefore(taskElement, placeholder);
            
            // Update checkbox state
            const checkbox = taskElement.querySelector('input[type="checkbox"]');
            if (checkbox) {
                checkbox.checked = (status === 'done');
            }
            
            // Update title text strike-through and opacity
            const titleSpan = taskElement.querySelector('.task-title');
            if (titleSpan) {
                if (status === 'done') {
                    titleSpan.classList.add('line-through', 'opacity-45');
                } else {
                    titleSpan.classList.remove('line-through', 'opacity-45');
                }
            }
            
            // Update data-status attribute on the edit button inside the card
            const editButton = taskElement.querySelector('button[onclick^="openTaskModal"]');
            if (editButton) {
                editButton.dataset.status = status;
            }
            
            // Update counts & placeholders client-side
            updateColumnCounts();
            
            // Send AJAX update
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(`/tareas/${taskId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    _method: 'PATCH',
                    status: status
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al actualizar la tarea');
                }
                return response.json();
            })
            .catch(error => {
                console.error(error);
                alert('No se pudo guardar el cambio de columna en el servidor. Recargando...');
                window.location.reload();
            });
        }

        function updateColumnCounts() {
            ['todo', 'doing', 'done'].forEach(status => {
                const column = document.getElementById(`column-${status}`);
                const countBadge = document.getElementById(`count-${status}`);
                const tasks = column.querySelectorAll('.task-card');
                countBadge.textContent = tasks.length;
                
                const placeholder = document.getElementById(`placeholder-${status}`);
                if (tasks.length === 0) {
                    placeholder.classList.remove('hidden');
                } else {
                    placeholder.classList.add('hidden');
                }
            });
        }
    </script>
@endpush
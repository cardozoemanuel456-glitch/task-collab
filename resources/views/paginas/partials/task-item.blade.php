<div data-id="{{ $tarea->id }}" draggable="true" ondragstart="drag(event)"
     class="flex items-center justify-between p-3 border rounded-xl transition relative cursor-grab active:cursor-grabbing shadow-sm
            {{ auth()->user()->dark_mode ? 'bg-[#121212] border-neutral-800 text-neutral-200 hover:border-teal-500/40' : 'bg-neutral-100 border-neutral-300 text-neutral-800 hover:border-teal-600/40' }}">
    
    <div id="task-desc-{{ $tarea->id }}" class="hidden">{{ $tarea->description }}</div>

    <div class="flex flex-col min-w-0 flex-1 pr-2">
        <span class="text-xs font-semibold truncate {{ $tarea->status === 'done' ? 'line-through opacity-45' : '' }}">
            {{ $tarea->title }}
        </span>
        
        <div class="flex items-center space-x-2 mt-1.5">
            @if(($tarea->priority ?? 'media') === 'alta')
                <span class="text-[9px] font-bold uppercase tracking-wide bg-red-500/10 text-red-500 px-1.5 py-0.5 rounded border border-red-500/20">Alta</span>
            @elseif(($tarea->priority ?? 'media') === 'media')
                <span class="text-[9px] font-bold uppercase tracking-wide bg-amber-500/10 text-amber-500 px-1.5 py-0.5 rounded border border-amber-500/20">Media</span>
            @else
                <span class="text-[9px] font-bold uppercase tracking-wide bg-green-500/10 text-green-500 px-1.5 py-0.5 rounded border border-green-500/20">Baja</span>
            @endif

            @if($tarea->end_date)
                <span class="text-[9px] text-neutral-400 font-medium flex items-center">
                    📅 {{ \Carbon\Carbon::parse($tarea->end_date)->format('d/m') }}
                </span>
            @endif
        </div>
    </div>

    <div class="relative inline-block text-left">
        <button onclick="toggleTaskDropdown('{{ $tarea->id }}', event)" 
                class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-200 p-1 rounded-lg transition cursor-pointer flex items-center justify-center">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
            </svg>
        </button>
        
        <div id="dropdown-task-{{ $tarea->id }}" 
             class="hidden absolute right-0 mt-1 w-32 rounded-xl shadow-lg py-1 border z-30 {{ auth()->user()->dark_mode ? 'bg-[#1e1e1e] border-neutral-800 text-neutral-300' : 'bg-white border-neutral-200 text-neutral-700' }}">
            
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
            
            <hr class="{{ auth()->user()->dark_mode ? 'border-neutral-800' : 'border-neutral-200' }}">
            
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